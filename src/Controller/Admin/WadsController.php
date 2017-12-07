<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

class WadsController extends AppController {

	public function index() {
		$wads = $this->Wads->find()
			->order(['name'])
			->all();

		$this->set('wads', $wads);
	}

	/**
	 * Add a wad entry
	 */
	public function add() {
		$wad = $this->Wads->newEntity();

		if ($this->request->is('post')) {
			$this->Wads->patchEntity($wad, $this->request->data());

			if ($this->Wads->save($wad)) {
				$this->Flash->success('Wad has been saved');
				$this->redirect(['action' => 'index']);
			}
		}

		$this->set('wad', $wad);
	}

	/**
	 * Edit a wad entry
	 */
	public function edit($id) {
		$wad = $this->Wads->get($id);
		if (empty($wad)) {
			throw new NotFoundException();
		}

		if ($this->request->is('put')) {
			$this->Wads->patchEntity($wad, $this->request->data());

			$saved = $this->Wads->save($wad);
			if ($saved) {
				$this->Flash->success('Wad has been edited');
				$this->redirect(['action' => 'index']);
			}
		}

		$this->set('wad', $wad);
	}

	/**
	 * Delete a wad entry
	 */
	public function delete($id) {
		$this->request->allowMethod(['delete']);

		$wad = $this->Wads->get($id);
		if (empty($wad)) {
			throw new NotFoundException();
		}

		$deleted = $this->Wads->delete($wad);
		if ($deleted) {
			$this->Flash->success('Wad deleted');
		} else {
			$this->Flash->error('Wad could not be deleted');
		}

		$this->redirect(['action' => 'index']);
	}
}
