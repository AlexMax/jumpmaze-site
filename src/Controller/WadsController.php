<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

class WadsController extends AppController {

	public function index() {
		$wads = $this->Wads->find()->all();

		$this->set('wads', $wads);
	}

	public function view($slug) {
		// Find the WAD we're looking at
		$wad = $this->Wads->find()
			->where(['slug' => $slug])
			->first();

		if ($wad === null) {
			throw new NotFoundException();
		}

		// Find all eligible maps (not team)
		$maps = $this->Wads->Maps->find()
			->select(['lump', 'name'])
			->where(['wad_id' => $wad->id])
			->where(function ($exp, $q) {
				return $exp->in('type', ['solo', 'solor']);
			})->order(['lump']);

		$emaps = [];
		foreach ($maps as $map) {
			$emaps[] = $map->lump.'_pbs';
		}

		$Zandronum = TableRegistry::get('Zandronum');

		// Find all players who have a personal best for every map
		$query = $Zandronum->find();
		$players = $query->select([
			'Count' => $query->func()->count('*'), 'KeyName'
		])->where(function($exp, $q) use ($emaps) {
			return $exp->in('Namespace', $emaps);
		})->group(['KeyName'])
			->having(['Count' => count($emaps)], ['Count' => 'integer']);

		// Only eligible players are used for our ranking query
		$eplayers = [];
		foreach ($players as $player) {
			$eplayers[] = $player->KeyName;
		}

		// Find total times for all eligible players
		$query = $Zandronum->find();
		$times = $query->select([
			'Time' => $query->func()->sum('Value'), 'KeyName'
		])->where(function($exp, $q) use ($emaps) {
			return $exp->in('Namespace', $emaps);
		})->where(function($exp, $q) use ($eplayers) {
			return $exp->in('KeyName', $eplayers);
		})->group(['KeyName'])->order(['Time']);

		// Also find every individual time for all eligible players
		$rawTimes = $Zandronum->find()
			->select(['Namespace', 'KeyName', 'Value'])
			->where(function($exp, $q) use ($emaps) {
			return $exp->in('Namespace', $emaps);
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

		$this->set('wad', $wad);
		$this->set('maps', $maps);
		$this->set('times', $times);
		$this->set('ranks', $ranks);
	}
}
