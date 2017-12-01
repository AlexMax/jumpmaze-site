<?php

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class HighscoreSoloForm extends Form {
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
