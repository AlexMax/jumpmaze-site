<h1>Jumpmaze Rankings</h1>
<h3>Ranked by total time across all 22 eligible maps</h3>
<table>
	<thead>
		<tr>
			<th>Player</th>
			<th>Total Time</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($times as $time): ?>
		<tr>
			<td>
				<a href="/players/<?= h($time->KeyName); ?>">
					<?= h($time->KeyName); ?>
				</a>
			</td>
			<td><?= $this->ticstime($time->Time); ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<h3>Ranked by average standing across all 22 eligible maps</h3>
<table>
	<thead>
		<tr>
			<th>Player</th>
			<th>Average Rank</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($ranks as $name => $rank): ?>
		<tr>
			<td>
				<a href="/players/<?= h($name); ?>">
					<?= h($name); ?>
				</a>
			</td>
			<td><?= number_format($rank, 2); ?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
