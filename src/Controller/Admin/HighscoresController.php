<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use App\Form\HighscoreSoloForm;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;

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

	public function edit($map) {
		// Find records for map
		$Zandronum = TableRegistry::get('Zandronum');
		$records = $Zandronum->find()
			->select(['rowid', 'Namespace', 'KeyName', 'Value'])
			->where(['Namespace' => $map])
			->all();

		if (empty($records)) {
			throw new NotFoundException();
		}

		// What kind of record are we dealing with?
		die($records->first()->KeyName);

		if (!empty($this->request->data)) {
			// Form submission
			die('submit');
		} else {
			// First load - convert records into a format the form understands
			$data = HighscoreSoloForm::recordsToFormData($records);
			$this->request->data = $data;
		}

		$form = new HighscoreSoloForm();
		$this->set('form', $form);
	}
}
