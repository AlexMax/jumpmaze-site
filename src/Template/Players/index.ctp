<table>
	<thead>
		<tr>
			<th>Player</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($players as $player): ?>
		<tr>
			<td>
				<a href="/players/<?= h($player->KeyName); ?>">
					<?= h($player->KeyName); ?>
				</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
