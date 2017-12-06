<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\View\View;

/**
 * Application View
 *
 * Your application’s default view class
 *
 * @link http://book.cakephp.org/3.0/en/views.html#the-app-view
 */
class AppView extends View
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */
    public function initialize()
    {
	}
	
	// Convert a time in tics to a real time
	public function ticstime($gametics) {
		$ms = (($gametics % 35) / 35) * 100;
		$secs = $gametics / 35;
		$mins = $secs / 60;
		$hours = $mins / 60;
		$secs %= 60;

		if ($hours >= 1) {
			$mins %= 60;
			return sprintf('%d:%02d:%02d.%02d', $hours, $mins, $secs, $ms);
		} else {
			return sprintf('%d:%02d.%02d', $mins, $secs, $ms);
		}
	}
}
