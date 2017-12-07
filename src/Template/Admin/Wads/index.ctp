<a href="/admin/wads/add">Add</a>
<table>
	<tr>
		<th>Name</th>
		<th>Operations</th>
	</tr>
	<?php foreach ($wads as $wad): ?>
	<tr>
		<td><?= h($wad->name); ?></td>
		<td>
			<a href="/admin/wads/edit/<?= h($wad->id); ?>">Edit</a>
			<?= $this->Form->postLink('Delete',
				['action' => 'delete', $wad->id], [
					'method' => 'delete',
					'confirm' => 'Sure?',
				]); ?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
