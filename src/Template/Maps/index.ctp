<?php

// Convert a time in tics to a real time
function ticstime($gametics) {
	$ms = (($gametics % 35) / 35) * 100;
	$secs = $gametics / 35;
	$mins = $secs / 60 % 60;
	$secs %= 60;

	return sprintf('%d:%02d.%02d', $mins, $secs, $ms);
}

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
			switch ($name[$i + 1]) {
			case 'f': $color = 'ffcc00'; break;
			case 'h': $color = '9999ff'; break;
			case 'j':
			case '-': $color = 'dfdfdf'; break;
			case 'v': $color = '00dddd'; break;
			case '[':
				// NewTextColors color
				$end = strpos($name, ']', $i);
				$colorname = substr($name, $i + 2, $end - $i - 2);

				switch ($colorname) {
				case 'x5': $color = 'd7fff7'; break;
				case 'm5': $color = 'b6b7ff'; break;
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
	}

	$colorized .= '</font>';

	return $colorized;
}

?><table>
	<tr>
		<th>Short Name</th>
		<th>Name</th>
		<th>Top Player</th>
		<th>Time</th>
	</tr>
	<?php foreach ($maps as $map): ?>
	<tr>
		<td><?= h($map->lump); ?></td>
		<td><?= h($map->name); ?></td>
		<?php if (isset($records[$map->lump])): ?>
		<td><?= colorize($records[$map->lump]['author']); ?></td>
		<td><?= ticstime($records[$map->lump]['time']); ?></td>
		<?php endif; ?>
	</tr>
	<?php endforeach; ?>
</table>
