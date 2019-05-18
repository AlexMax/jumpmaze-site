<?php

$types = [
	'jrs_hs_rdate' => 'Solo',
	'jrt_hs_rdate' => 'Team',
	'JMR_hs_rdate' => 'Solo (Run)',
];

?><a href="#">Add</a>
<table>
	<tr>
		<th>Map Name</th>
		<th>Type</th>
		<th>Operations</th>
	</tr>
	<?php foreach ($records as $record): ?>
	<tr>
		<td><?= h($record->Namespace); ?></td>
		<td><?= h($types[$record->KeyName]); ?></td>
		<td>
			<a href="/admin/highscores/edit/<?= h($record->Namespace); ?>">Edit</a>
			<a href="/admin/highscores/delete/<?= h($record->Namespace); ?>">Delete</a>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
