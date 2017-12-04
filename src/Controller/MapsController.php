<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

class MapsController extends AppController {

	public function index() {
		// Get the list of maps
		$Map = TableRegistry::get('Maps');
		$mapNames = $Map->find('list', [
			'keyField' => 'lump',
			'valueField' => 'name',
		])->toArray();

		// Grab the list of high scores for all maps
		$Zandronum = TableRegistry::get('Zandronum');
		$recordRows = $Zandronum->find()
			->select(['rowid', 'Namespace', 'KeyName', 'Value'])
			->where(['Namespace NOT LIKE' => '%_pbs'])
			->where(function($exp, $q) {
				return $exp->in('KeyName', [
					'jrs_hs_author', 'jrs_hs_time', 'jrt_hs_time',
					'JMR_hs_author', 'JMR_hs_time']);
			})
			->orWhere(['KeyName LIKE' => 'jrt_hs_helper_%'])
			->order('Namespace')
			->all();

		// Massage results into easy-to-lookup data structure - makes
		// things eaiser for the view.
		$records = [];
		foreach ($recordRows as $record) {
			$ns = $record->Namespace;
			if (!isset($records[$ns])) {
				$records[$ns] = [];

				// Do we have a map name?  Add it!
				if (isset($mapNames[$ns])) {
					$records[$ns]['name'] = $mapNames[$ns];
					unset($mapNames[$ns]);
				}
			}

			$keyname = $record->KeyName;
			if (strpos($keyname, 'jrt_hs_helper_') === 0) {
				// Fold helpers into one key name
				$keyname = 'jrt_hs_helper';
			}

			switch ($keyname) {
			case 'jrs_hs_author':
			case 'JMR_hs_author':
				$records[$ns]['author'] = $record->Value;
				break;
			case 'jrt_hs_helper':
				if (!isset($records[$ns]['author'])) {
					$records[$ns]['author'] = [];
				}
				$records[$ns]['author'][] = $record->Value;
				break;
			case 'jrs_hs_time':
			case 'JMR_hs_time':
			case 'jrt_hs_time':
				$records[$ns]['time'] = $record->Value;
				break;
			default:
				throw new \Exception('Unexpected row data');
			}
		}

		// Any remaining maps have no times set, add them.
		foreach ($mapNames as $key => $value) {
			$records[$key] = [
				'name' => $value,
			];
		}

		// Sort the entire list, with MAP## coming first
		uksort($records, function($a, $b) {
			$amap = (strpos($a, 'MAP') === 0);
			$bmap = (strpos($b, 'MAP') === 0);

			if ($amap && !$bmap) {
				return -1;
			} elseif (!$amap && $bmap) {
				return 1;
			} else {
				return strcmp($a, $b);
			}
		});

		$this->set('records', $records);
	}

	public function view($lump) {
		// Get a specific map
		$Maps = TableRegistry::get('Maps');
		$map = $Maps->find()->where(['lump' => $lump])->first();

		// Get the personal bests for a map
		$Zandronum = TableRegistry::get('Zandronum');
		$records = $Zandronum->find()
			->select(['rowid', 'Namespace', 'KeyName', 'Value'])
			->where(['namespace' => $lump.'_pbs'])
			->order(['value']);

		$this->set('map', $map);
		$this->set('records', $records);
	}
}
