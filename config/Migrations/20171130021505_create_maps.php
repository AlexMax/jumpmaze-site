<?php

use Migrations\AbstractMigration;

class CreateMaps extends AbstractMigration {

	public function change() {
		$table = $this->table('maps');
		$table->addColumn('lump', 'string', [
			'null' => false,
		]);
		$table->addColumn('name', 'string', [
			'null' => false,
		]);
		$table->addColumn('author', 'string', [
			'null' => false,
		]);
		$table->addColumn('type', 'string', [
			'null' => false,
		]);
		$table->addColumn('difficulty', 'string', [
			'null' => false,
		]);
		$table->addColumn('par', 'string', [
			'null' => false,
		]);
		$table->create();
	}
}
