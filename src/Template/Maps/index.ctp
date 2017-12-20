<?php

// Colorize a high-score nickname
function colorize($name) {
	$colorized = '<span style="color:#dfdfdf;">';

	$i = 0;
	$len = strlen($name);

	for (;;) {
		$ch = $name[$i];
		if ($ch !== "\x1c") {
			// Colorless - just add the char
			$colorized .= h($ch);
			$i += 1;
		} else {
			// Color escape - figure out the color
			$color = null;
			switch (strtolower($name[$i + 1])) {
			case 'a': $color = 'cc3333'; break;
			case 'c': $color = 'cccccc'; break;
			case 'f': $color = 'ffcc00'; break;
			case 'g': $color = 'ff5566'; break;
			case 'h': $color = '9999ff'; break;
			case 'i': $color = 'ffaa00'; break;
			case 'j':
			case 'l':
			case '-': $color = 'dfdfdf'; break;
			case 'k': $color = 'eeee33'; break;
			case 'm': $color = '000000'; break;
			case 'n': $color = '33eeff'; break;
			case 'o': $color = 'ffcc99'; break;
			case 'q': $color = '008c00'; break;
			case 'r': $color = '800000'; break;
			case 't': $color = '9966cc'; break;
			case 'u': $color = '808080'; break;
			case 'v': $color = '00dddd'; break;
			case '[':
				// NewTextColors color
				$end = strpos($name, ']', $i);
				$colorname = strtolower(substr($name, $i + 2, $end - $i - 2));

				switch ($colorname) {
				case 'a5': $color = '4ede47'; break;
				case 'c0': $color = 'f14ed8'; break;
				case 'c1': $color = '00d8ff'; break;
				case 'g1': $color = 'ffffff'; break;
				case 'g2': $color = '27212d'; break;
				case 'h0': $color = 'd0c1cf'; break;
				case 'j2': $color = 'fffdfe'; break;
				case 'l2': $color = '0ab45a'; break;
				case 'l4': $color = 'ffef00'; break;
				case 'm2': $color = 'ffdbc8'; break;
				case 'm5': $color = 'b6b7ff'; break;
				case 'm6': $color = 'ffd2d2'; break;
				case 'p7': $color = '6fbffd'; break;
				case 'q0': $color = 'fab7ed'; break;
				case 't8': $color = 'ff36fd'; break;
				case 'u3': $color = 'ff3600'; break;
				case 'v2': $color = 'ffffff'; break;
				case 'w2': $color = 'ffffff'; break;
				case 'x5': $color = 'd7fff7'; break;
				case 'y2': $color = '06480c'; break;
				case 'z6': $color = 'ffceef'; break;
				default: die($colorname);
				}

				// Skip past the color name, plus the ending bracket.
				// Starting bracket and escape are skipped later.
				$i += strlen($colorname) + 1;

				break;
			default: die($name[$i + 1]);
			}

			if ($color !== null) {
				$colorized .= sprintf('</span><span style="color:#%s;">', $color);
			}

			// Skip past the color
			$i += 2;
		}

		if ($i == $len) {
			break;
		}

		if ($i > $len) {
			die('too far');
		}
	}

	$colorized .= '</span>';

	return $colorized;
}

?>
<p>
	We are aware of the...insensitive nature of some of these nicknames.  Moderation is forthcoming. -The Management
</p>
<?php foreach ($records as $wad): ?>
<h2><?= h($wad['name']); ?></h2>
<table>
	<thead>
		<tr>
			<th>Name</th>
			<th>Top Player</th>
			<th>Top Time</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($wad['maps'] as $key => $record): ?>
		<tr>
			<?php if (isset($record['name'])): ?>
			<td>
				<a href="/maps/<?= h($key); ?>">
					<?= h($key); ?>: <?= h($record['name']); ?></td>
				</a>
			<?php else: ?>
			<td><?= h($key); ?></td>
			<?php endif; ?>
			<?php if (isset($record['author'])): ?>
			<?php if (is_array($record['author'])): ?>
			<td>
				<?php
				echo implode(', ', array_map('colorize', $record['author']));
				?>
			</td>
			<?php else: ?>
			<td><?= colorize($record['author']); ?></td>
			<?php endif; ?>
			<?php else: ?>
			<td>&mdash;</td>
			<?php endif; ?>
			<?php if (isset($record['time'])): ?>
			<td><?= $this->ticstime($record['time']); ?></td>
			<?php else: ?>
			<td>&mdash;</td>
			<?php endif; ?>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php endforeach; ?>
