<?php

use App\Model\Table\MapsTable;

?><h3><?= h($map->name); ?></h3>
<dl>
	<dt>Short name:</dt>
	<dd> <?= h($map->lump); ?></dd>
	<dt>Author:</dt>
	<dd><?= h($map->author); ?></dd>
	<dt>Map Type:</dt>
	<dd><?= MapsTable::typeOptions()[$map->type]; ?></dd>
	<dt>Difficulty:</dt>
	<dd><?= MapsTable::difficultyOptions()[$map->difficulty]; ?></dd>
	<dt>Par Time:</dt>
	<dd><?= h($map->par); ?></dd>
</dl>
<table>
	<tr>
		<th>Player</th>
		<th>Time</th>
		<th>Date</th>
	</tr>
	<?php foreach ($records as $record): ?>
	<tr>
		<td><?= h($record->KeyName); ?></td>
		<td><?= $this->ticstime($record->Value); ?></td>
		<td><?= date('F j, Y', $record->Timestamp); ?></td>
	</tr>
	<?php endforeach; ?>
</table>
