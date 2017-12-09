<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class WadsTable extends Table {

	public function initialize(array $config) {
		$this->hasMany('Maps');
	}
}
