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
				return $exp->in('KeyName', ['jrs_hs_author', 'jrs_hs_time']);
			})
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

			switch ($record->KeyName) {
			case 'jrs_hs_author':
				$records[$ns]['author'] = $record->Value;
				break;
			case 'jrs_hs_time':
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
}
