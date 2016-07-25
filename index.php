<?php

 /**
  * Battle Ships
  * Game implementation for Techhudle
  * 
  * @version 1.0
  * @author Svetoslav Dragoev
  */

namespace Battleships

ob_start();
ini_set('default_charset', 'utf-8');

require(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'core.php');

try {
	// DA IMPLEMENTIRAM REQUEST!?!
	$game = 'cli' == Config()->ENVIRONMENT ? new ConsoleController() : new WebController;
	$game->start();
} catch(Exception $e) {
	echo $e->getMessage();
}