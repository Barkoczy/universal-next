<?php
/**
* @copyright  2022
* @package    UNIVERSAL next
* @version	  0.0.1
* @access	    private
* @see        https://universal-next.herokuapp.com
* @see        https://github.com/Barkoczy/universal-next
* @author     Henrich Barkoczy <henrich.barkoczy@tutanota.com>
* @license    https://mit-license.org
*/
declare(strict_types=1);

// @autoload
require __DIR__ . '/../vendor/autoload.php';

// @session
session_start();

// @boot
App\Bootstrap::boot()->run();