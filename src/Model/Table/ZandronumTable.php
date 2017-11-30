<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class ZandronumTable extends Table {

	public static function defaultConnectionName() {
		return 'jumpmaze';
	}

	public function initialize(array $config) {
		$this->primaryKey('rowid');
	}
}
