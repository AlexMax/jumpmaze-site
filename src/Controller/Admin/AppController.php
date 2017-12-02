<?php

namespace App\Controller\Admin;

use Cake\Controller\Controller;

class AppController extends Controller {

	public function initialize() {
		$this->loadComponent('Auth', [
			'authenticate' => 'Hardcoded',
			'storage' => 'Memory',
			'unauthorizedRedirect' => false,
		]);
		$this->loadComponent('Flash');
	}
}
