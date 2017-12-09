<?php $this->assign('title', h($wad->name).' Rankings'); ?>
<div class="two-columns">
	<div class="item">
		<h3>Ranked by total time across all eligible maps</h3>
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
	</div>
	<div class="item">
		<h3>Ranked by average standing across all eligible maps</h3>
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
	</div>
</div>
<h3>Eligible Map List</h3>
<ul>
	<?php foreach ($maps as $map): ?>
	<li>
		<a href="/maps/<?= h($map->lump); ?>">
			<?= h($map->lump); ?> - <?= h($map->name); ?>
		</a>
	</li>
	<?php endforeach; ?>
</ul>
