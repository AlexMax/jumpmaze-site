<?php

use App\Model\Table\MapsTable;

?><?= $this->Form->create($map); ?>
<?= $this->Form->input('lump'); ?>
<?= $this->Form->input('name'); ?>
<?= $this->Form->input('author'); ?>
<?= $this->Form->input('type', [
	'options' => MapsTable::typeOptions(),
]); ?>
<?= $this->Form->input('difficulty', [
	'options' => MapsTable::difficultyOptions(),
]); ?>
<?= $this->Form->input('par'); ?>
<?= $this->Form->submit(); ?>
<?= $this->Form->end(); ?>
