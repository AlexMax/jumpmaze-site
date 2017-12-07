<?php

use Phinx\Migration\AbstractMigration;

class CreateWads extends AbstractMigration {

	public function change() {
		$table = $this->table('wads');
		$table->addColumn('name', 'string', [
			'null' => false,
		]);
		$table->addColumn('slug', 'string', [
			'null' => false,
		]);
		$table->create();

		$maps = $this->table('maps');
		$maps->addColumn('wad_id', 'integer', [
			'null' => true,
		]);
		$maps->addForeignKey('wad_id', 'wads', 'id');
		$maps->save();
    }
}
