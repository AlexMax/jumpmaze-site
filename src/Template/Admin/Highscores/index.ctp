<a href="#">Add</a>
<table>
	<tr>
		<th>Map Name</th>
		<th>Type</th>
		<th>Operations</th>
	</tr>
	<?php foreach ($records as $record): ?>
	<tr>
		<td><?= h($record->Namespace); ?></td>
		<td><?= h($record->KeyName); ?></td>
		<td>
			<a href="#">Edit</a>
			<a href="#">Delete</a>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
