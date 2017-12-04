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
			case 'a': $color = 'cc3333'; break;
			case 'c': $color = 'cccccc'; break;
			case 'f': $color = 'ffcc00'; break;
			case 'g': $color = 'ff5566'; break;
			case 'h': $color = '9999ff'; break;
			case 'j':
			case '-': $color = 'dfdfdf'; break;
			case 'm': $color = '000000'; break;
			case 'q': $color = '008c00'; break;
			case 'r': $color = '800000'; break;
			case 't': $color = '9966cc'; break;
			case 'v': $color = '00dddd'; break;
			case '[':
				// NewTextColors color
				$end = strpos($name, ']', $i);
				$colorname = substr($name, $i + 2, $end - $i - 2);

				switch ($colorname) {
				case 'j2': $color = 'fffdfe'; break;
				case 'm5': $color = 'b6b7ff'; break;
				case 'x5': $color = 'd7fff7'; break;
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

?><table>
	<tr>
		<th>Name</th>
		<th>Top Player</th>
		<th>Top Time</th>
	</tr>
	<?php foreach ($records as $key => $record): ?>
	<tr>
		<?php if (isset($record['name'])): ?>
		<td><?= h($key); ?>: <?= h($record['name']); ?></td>
		<?php else: ?>
		<td><?= h($key); ?></td>
		<?php endif; ?>
		<?php if (isset($record['author'])): ?>
		<?php if (is_array($record['author'])): ?>
		<td><?= implode(', ', array_map('colorize', $record['author'])); ?></td>
		<?php else: ?>
		<td><?= colorize($record['author']); ?></td>
		<?php endif; ?>
		<?php else: ?>
		<td>&mdash;</td>
		<?php endif; ?>
		<?php if (isset($record['time'])): ?>
		<td><?= ticstime($record['time']); ?></td>
		<?php else: ?>
		<td>&mdash;</td>
		<?php endif; ?>
	</tr>
	<?php endforeach; ?>
</table>
