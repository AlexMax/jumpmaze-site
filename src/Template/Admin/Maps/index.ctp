<a href="/admin/maps/add">Add</a>
<table>
	<tr>
		<th>Lump</th>
		<th>Name</th>
		<th>Operations</th>
	</tr>
	<?php foreach ($maps as $map): ?>
	<tr>
		<td><?= h($map->lump); ?></td>	
		<td><?= h($map->name); ?></td>	
		<td>
			<a href="/admin/maps/edit/<?= h($map->id); ?>">Edit</a>
			<?= $this->Form->postLink('Delete',
				['action' => 'delete', $map->id], [
					'method' => 'delete',
					'confirm' => 'Sure?',
				]); ?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
