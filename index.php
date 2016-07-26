<?php

/**
 * Battle Ships
 * Game implementation for Techhudle
 * 
 * @link https://en.wikipedia.org/wiki/Battleship_(game)
 * @version 1.0
 * @author Svetoslav Dragoev
 */

ob_start();
ini_set('default_charset', 'utf-8');

// load core
require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'core.php');

try {
  $request = is_cli() ? new CliRequest() : new WebRequest();
  $request->dispatch();
} catch(Exception $e) {
	echo $e->getMessage();
}