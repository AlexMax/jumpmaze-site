<?php

use App\Model\Table\MapsTable;

$this->assign('title', h($map->name));

?><div class="two-columns">
	<div class="item">
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
			<td>
				<a href="/players/<?= h($record->KeyName); ?>">
					<?= h($record->KeyName); ?>
				</a>
			</td>
			<td><?= $this->ticstime($record->Value); ?></td>
			<td><?= date('F j, Y', $record->Timestamp); ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
