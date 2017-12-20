<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

class MapsController extends AppController {

	public function index() {
		// Grab the list of high scores for all maps
		$Zandronum = TableRegistry::get('Zandronum');
		$recordRows = $Zandronum->find()
			->select(['rowid', 'Namespace', 'KeyName', 'Value'])
			->where(['Namespace NOT LIKE' => '%_pbs'])
			->where(function($exp, $q) {
				return $exp->in('KeyName', [
					'jrs_hs_author', 'jrs_hs_time', 'jrt_hs_time',
					'JMR_hs_author', 'JMR_hs_time', 'jrt_hs_total_players']);
			})
			->orWhere(['KeyName LIKE' => 'jrt_hs_helper_%'])
			->order('Namespace')
			->all();

		// Massage results into easy-to-lookup data structure
		$mapRecords = [];
		foreach ($recordRows as $record) {
			$ns = $record->Namespace;
			if (!isset($mapRecords[$ns])) {
				$mapRecords[$ns] = [];
			}

			$keyname = $record->KeyName;
			if (strpos($keyname, 'jrt_hs_helper_') === 0) {
				// Fold helpers into one key name
				$keyname = 'jrt_hs_helper';
				// Save helper # for later
				$helpernum = substr($record->KeyName, 14);
			}

			switch ($keyname) {
			case 'jrs_hs_author':
			case 'JMR_hs_author':
				// Normal author
				$mapRecords[$ns]['author'] = $record->Value;
				break;
			case 'jrt_hs_helper':
				// Team authors
				if (!isset($mapRecords[$ns]['author'])) {
					$mapRecords[$ns]['author'] = [];
				}

				// Ensure that we insert the author in the proper place,
				// so we can trim the list correctly later.
				$mapRecords[$ns]['author'] += [$helpernum => $record->Value];
				break;
			case 'jrt_hs_total_players':
				$mapRecords[$ns]['count'] = $record->Value;
				break;
			case 'jrs_hs_time':
			case 'JMR_hs_time':
			case 'jrt_hs_time':
				$mapRecords[$ns]['time'] = $record->Value;
				break;
			default:
				throw new \Exception('Unexpected row data');
			}
		}

		// Our final, sorted-by-wad records
		$records = [];

		// Get the list of WADs
		$wads = $this->Maps->Wads->find()
			->order(['id']);
		foreach ($wads as $wad) {
			$records[$wad->id] = [
				'name' => $wad->name,
				'maps' => [],
			];
		}

		// Get the list of maps in those WADs
		$maps = $this->Maps->find()
			->where(function($exp, $q) use ($records) {
				return $exp->in('wad_id', array_keys($records));
			})
			->orWhere(function($exp, $q) {
				return $exp->isNull('wad_id');
			})
			->order(['lump']);

		// A place for the unaffiliated maps
		$records['NULL'] = [
			'name' => 'Unaffiliated Maps',
			'maps' => [],
		];

		foreach ($maps as $map) {
			if ($map->wad_id === null) {
				$key = 'NULL';
			} else {
				$key = $map->wad_id;
			}
			$records[$key]['maps'][$map->lump] = [
				'name' => $map->name,
			];

			if (isset($mapRecords[$map->lump])) {
				$authors = $mapRecords[$map->lump]['author'];
				if (is_array($authors)) {
					// Trim author list if too long...
					ksort($authors);
					$authors = array_slice($authors, 0, $mapRecords[$map->lump]['count']);
				}
				$records[$key]['maps'][$map->lump]['author'] = $authors;
				$records[$key]['maps'][$map->lump]['time'] = $mapRecords[$map->lump]['time'];
			}
		}

		$this->set('records', $records);
	}

	public function view($lump) {
		// Get a specific map
		$Maps = TableRegistry::get('Maps');
		$map = $Maps->find()->where(['lump' => $lump])->first();

		// Get the personal bests for a map
		$Zandronum = TableRegistry::get('Zandronum');
		$records = $Zandronum->find()
			->select(['rowid', 'Namespace', 'KeyName', 'Value', 'Timestamp'])
			->where(['namespace' => $lump.'_pbs'])
			->order(['CAST(value AS INTEGER)']);

		$this->set('map', $map);
		$this->set('records', $records);
	}
}
