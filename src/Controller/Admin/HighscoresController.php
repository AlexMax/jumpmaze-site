<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\ORM\TableRegistry;

class HighscoresController extends AppController {

	public function index() {
		// Find all high scores.  There are three types of high scores,
		// but all of them contain a date field, so we search for that.
		$Zandronum = TableRegistry::get('Zandronum');
		$records = $Zandronum->find()
			->select(['rowid', 'Namespace', 'KeyName'])
			->where(['KeyName LIKE' => '%_rdate'])
			->order('Namespace')
			->all();

		$this->set('records', $records);
	}
}
