<?php $this->assign('title', 'Top WAD Rankings'); ?>
<p>
	Find out who has the lowest time and highest average ranking across
	all of the maps in a single WAD.
</p>
<?php foreach ($wads as $wad): ?>
<h2>
	<a href="/wads/<?= h($wad->slug); ?> ">
		<?= h($wad->name); ?>
	</a>
</h2>
<?php endforeach; ?>
