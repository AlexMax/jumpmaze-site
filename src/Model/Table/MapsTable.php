<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class MapsTable extends Table {

	public function initialize(array $config) {
		$this->belongsTo('Wads');
	}

	public static function typeOptions() {
		return [
			'solo' => 'Solo',
			'solor' => 'Solo (Run)',
			'team' => 'Team',
		];
	}

	public static function difficultyOptions() {
		return [
			'veasy' => 'Very Easy',
			'easy' => 'Easy',
			'moderate' => 'Moderate',
			'hard' => 'Hard',
			'vhard' => 'Very Hard',
			'varied' => 'Varied',
		];
	}
}
