<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

class WadsController extends AppController {

	public function index() {
		$jm1 = [];
		for ($i = 1;$i <= 32;$i++) {
			$jm1[] = sprintf('MAP%02d_pbs', $i);
		}
		$jm1_count = 22;

		$Zandronum = TableRegistry::get('Zandronum');

		// Find all players who have a personal best for every map
		$query = $Zandronum->find();
		$players = $query->select([
			'Count' => $query->func()->count('*'), 'KeyName'
		])->where(function($exp, $q) use ($jm1) {
			return $exp->in('Namespace', $jm1);
		})->group(['KeyName'])
			->having(['Count' => $jm1_count], ['Count' => 'integer']);

		// Only eligible players are used for our ranking query
		$eplayers = [];
		foreach ($players as $player) {
			$eplayers[] = $player->KeyName;
		}

		// Find total times for all eligible players
		$query = $Zandronum->find();
		$times = $query->select([
			'Time' => $query->func()->sum('Value'), 'KeyName'
		])->where(function($exp, $q) use ($jm1) {
			return $exp->in('Namespace', $jm1);
		})->where(function($exp, $q) use ($eplayers) {
			return $exp->in('KeyName', $eplayers);
		})->group(['KeyName'])->order(['Time']);

		// Also find every individual time for all eligible players
		$rawTimes = $Zandronum->find()
			->select(['Namespace', 'KeyName', 'Value'])
			->where(function($exp, $q) use ($jm1) {
			return $exp->in('Namespace', $jm1);
		})->order(['CAST(Value AS INTEGER)']);

		// Figure out everybody's rank on every map.
		$mapRanks = [];
		$ranks = [];
		foreach ($rawTimes as $rawTime) {
			// Push the rank number up for every high score entry
			$mapName = $rawTime->Namespace;
			if (!isset($mapRanks[$mapName])) {
				$mapRanks[$mapName] = 1;
			} else {
				$mapRanks[$mapName] += 1;
			}
			$rank = $mapRanks[$mapName];

			// If the ranked person isn't eligible, we don't care
			if (!in_array($rawTime->KeyName, $eplayers)) {
				continue;
			}

			// Add the current rank to the list of ranks
			if (!isset($ranks[$rawTime->KeyName])) {
				$ranks[$rawTime->KeyName] = [];
			}
			$ranks[$rawTime->KeyName][] = $rank;
		}

		// Turn times into average
		foreach ($ranks as $key => $value) {
			$ranks[$key] = array_sum($ranks[$key]) / count($ranks[$key]);
		}
		asort($ranks);

		$this->set('times', $times);
		$this->set('ranks', $ranks);
	}
}
