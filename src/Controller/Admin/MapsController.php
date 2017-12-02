<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Network\Exception\NotFoundException;

class MapsController extends AppController {

	public function index() {
		$maps = $this->Maps->find()
			->order(['lump'])
			->all();

		$this->set('maps', $maps);
	}

	/**
	 * Add a map entry
	 */
	public function add() {
		$map = $this->Maps->newEntity();

		if ($this->request->is('post')) {
			$this->Maps->patchEntity($map, $this->request->data());

			if ($this->Maps->save($map)) {
				$this->Flash->success('Map has been saved');
				$this->redirect(['action' => 'index']);
			}
		}

		$this->set('map', $map);
	}

	/**
	 * Edit an existing map entry
	 */
	public function edit($id) {
		$map = $this->Maps->get($id);
		if (empty($map)) {
			throw new NotFoundException();
		}

		if ($this->request->is('put')) {
			$this->Maps->patchEntity($map, $this->request->data());
			
			$saved = $this->Maps->save($map);
			if ($saved) {
				$this->Flash->success('Map has been edited');
				$this->redirect(['action' => 'index']);
			}
		}

		$this->set('map', $map);
	}

	/**
	 * Delete a map entry
	 */
	public function delete($id) {
		$this->request->allowMethod(['delete']);

		$map = $this->Maps->get($id);
		if (empty($map)) {
			throw new NotFoundException();
		}

		$deleted = $this->Maps->delete($map);
		if ($deleted) {
			$this->Flash->success('Map deleted');
		} else {
			$this->Flash->error('Map could not be deleted');
		}

		$this->redirect(['action' => 'index']);
	}
}
