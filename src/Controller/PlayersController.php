<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class PlayersController extends AppController {

	public function index() {
		$Zandronum = TableRegistry::get('Zandronum');

		$players = $Zandronum->find()
			->select(['rowid', 'KeyName'])
			->where(['Namespace GLOB' => '*_pbs'])
			->group(['KeyName'])
			->order(['KeyName'])->all();

		$this->set('players', $players);
	}

	public function view($player) {
		$Zandronum = TableRegistry::get('Zandronum');

		$records = $Zandronum->find()
			->select(['rowid', 'Namespace', 'Value', 'Timestamp'])
			->where(['Namespace GLOB' => '*_pbs'])
			->where(['KeyName' => $player])
			->order(['Namespace'])
			->toArray();

		$this->set('records', $records);
	}
}
