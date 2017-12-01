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

	public function edit($id) {
		// Find the specific date record that "anchors" the others.
		$Zandronum = TableRegistry::get('Zandronum');
		$record = $Zandronum->find()
			->select(['rowid', 'Namespace', 'KeyName', 'Value'])
			->where(['rowid' => $id])
			->where(['KeyName LIKE' => '%_rdate'])
			->first();

		if (empty($record)) {
			throw new NotFoundException();
		}

		// Find all associated records and inject them into the form.
		$data = [];
		$assocs = $Zandronum->find()
			->select(['rowid', 'Namespace', 'KeyName', 'Value'])
			->where(['Namespace' => $record->Namespace])
			->all();
		foreach ($assocs as $assoc) {
			switch ($assoc->KeyName) {
			case 'jrs_hs_time': // Time
			case 'JMR_hs_time':
				$this->request->data('time', $assoc->Value);
				break;
			case 'jrs_hs_author': // Colored name
			case 'JMR_hs_author':
				$author = str_replace("\x1c", '\c', $assoc->Value);
				$author = substr($author, 0, strlen($author) - 3);
				$this->request->data('author', $author);
				break;
			case 'jrs_hs_rdate': // Record Date
			case 'JMR_hs_rdate':
				$rdate = sprintf('%s-%s-%s',
					substr($assoc->Value, 0, 4),
					substr($assoc->Value, 4, 2),
					substr($assoc->Value, 6, 2));
				$this->request->data('rdate', $rdate);
				break;
			default:
				throw new \Exception('Unexpected row data');
			}

			$data[$assoc->KeyName] = $assoc->Value;
		}

		$form = new HighscoreSoloForm();
		$this->set('form', $form);
	}
}
