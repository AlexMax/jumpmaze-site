<?php

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class HighscoreSoloForm extends Form {

	public static function recordsToFormData($records) {
		$data = [];
		foreach ($records as $record) {
			switch ($record->KeyName) {
			case 'jrs_hs_time': // Time
			case 'JMR_hs_time':
				$data['time'] = $record->Value;
				break;
			case 'jrs_hs_author': // Colored name
			case 'JMR_hs_author':
				$author = str_replace("\x1c", '\c', $record->Value);
				$author = substr($author, 0, strlen($author) - 3);
				$data['author'] = $author;
				break;
			case 'jrs_hs_rdate': // Record Date
			case 'JMR_hs_rdate':
				$rdate = sprintf('%s-%s-%s',
					substr($record->Value, 0, 4),
					substr($record->Value, 4, 2),
					substr($record->Value, 6, 2));
				$data['rdate'] = $rdate;
				break;
			default:
				throw new \Exception('Unexpected row data');
			}
		}

		return $data;
	}

	protected function _buildSchema(Schema $schema) {
		return $schema->addField('author', 'string')
			->addField('time', 'integer')
			->addField('rdate', 'date');
	}

	protected function _buildValidator(Validator $validator) {
		return $validator;
	}

	protected function _execute(array $data) {
		return true;
	}
}
