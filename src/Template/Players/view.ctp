<table>
	<thead>
		<tr>
			<th>Map Name</th>
			<th>Time</th>
			<th>Date</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($records as $record): ?>
		<tr>
			<td><?= h($record->Namespace); ?></td>
			<td><?= $this->ticstime($record->Value); ?></td>
			<td><?= date('F j, Y', $record->Timestamp); ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
