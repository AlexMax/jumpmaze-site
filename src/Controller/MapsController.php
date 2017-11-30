<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

class MapsController extends AppController {

	public function index() {
		$Map = TableRegistry::get('Maps');
		$maps = $Map->find()->order('lump')->all();

		$Zandronum = TableRegistry::get('Zandronum');
		$recordRows = $Zandronum->find()
			->select(['rowid', 'Namespace', 'KeyName', 'Value'])
			->where(['Namespace NOT LIKE' => '%_pbs'])
			->where(function($exp, $q) {
				return $exp->in('KeyName', ['jrs_hs_author', 'jrs_hs_time']);
			})
			->order('Namespace')
			->all();

		$records = [];
		foreach ($recordRows as $record) {
			$ns = $record->Namespace;
			if (!isset($records[$ns])) {
				$records[$ns] = [];
			}

			switch ($record->KeyName) {
			case 'jrs_hs_author':
				$records[$ns]['author'] = $record->Value;
				break;
			case 'jrs_hs_time':
				$records[$ns]['time'] = $record->Value;
				break;
			default:
				throw \Exception('Unexpected row data');
			}
		}

		$this->set('maps', $maps);
		$this->set('records', $records);
	}
}
