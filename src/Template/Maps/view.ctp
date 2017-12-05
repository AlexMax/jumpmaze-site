<?php

use App\Model\Table\MapsTable;

?><div class="two-columns">
	<div class="item">
		<h3><?= h($map->name); ?></h3>
		<p>
			<strong>Short Name:</strong> <?= h($map->lump); ?><br>
			<strong>Author:</strong> <?= h($map->author); ?><br>
			<strong>Map Type:</strong> <?= MapsTable::typeOptions()[$map->type]; ?><br>
			<strong>Difficulty:</strong> <?= MapsTable::difficultyOptions()[$map->difficulty]; ?><br>
			<strong>Par Time:</strong> <?= h($map->par); ?>
		</p>
	</div>
	<img class="item" width="400" height="300" src="/img/maps/<?= h($map->lump); ?>.png">
</div>
<table>
	<thead>
		<tr>
			<th>Player</th>
			<th>Time</th>
			<th>Date</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($records as $record): ?>
		<tr>
			<td><?= h($record->KeyName); ?></td>
			<td><?= $this->ticstime($record->Value); ?></td>
			<td><?= date('F j, Y', $record->Timestamp); ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
