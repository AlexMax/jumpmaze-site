<?php

namespace App\Model\Table;

use Cake\ORM\Table;

/**
 * ZandronumTable is a CakePHP ORM wrapper around the Zandronum database
 * file.
 *
 * FIXME: Zandronum's database does not contain an 'id' field, which is
 *        expected of every CakePHP table.  SQList does give every table an
 *        invisible `rowid` column that does the trick, but it must be
 *        selected for by name, otherwise it does not appear, and things
 *        can get screwy when Cake can't find the id of an entity.
 *
 *        Hopefully at some point I will create a workaround for this...
 */
class ZandronumTable extends Table {

	public static function defaultConnectionName() {
		return 'jumpmaze';
	}

	public function initialize(array $config) {
		$this->primaryKey('rowid');
	}
}
