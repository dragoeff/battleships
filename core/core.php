<?php
/**
 * Core file which include all needed base classes for the game
 *
 * @author Svetoslav Dragoev
 */

define('DS', DIRECTORY_SEPARATOR);

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 'on');

mb_internal_encoding('utf-8');


/**
 * Basis files which have to be included
 *
 * @return array
 */
function getCoreComponents() {
	return ['Config', 'DataManagement', 'ShipFactory', 'Ship'];
}

foreach (getCoreComponents() as $file) {
	require(dirname(__FILE__) . DS . $file . '.php');
}

/**
 * Autoload function automatically called in case you are trying to
 * use a class which hasnt been defined yet.
 *
 * @throws ClassMissingException if $class_name not found
 */
function autoload($class_name) {
	// set namespace if exists
	if (($pos = strrpos($class_name, '\\')) !== false) {
		$namespace = substr($class_name, 0, $pos);
		$class_name = substr($class_name, $pos + 1);

		$namespace = trim(str_replace('\\', DS, strtolower($namespace)), DS) . DS;
	} else {
		$namespace = '';
	}

	// make sure class name is camelcase
	$class_name = ucwords($class_name);

	// if requested class is controller and such file exists request it
	if ((substr($class_name, -10) === 'Controller')) {
		if (
			(($file = Config()->CONTROLLERS_PATH . $namespace . $class_name . '.php') && file_exists($file) && require($file))
			|| (($file = Config()->CONTROLLERS_PATH . $class_name . '.php') && file_exists($file) && require($file))
		) {
			goto end;
		}
	}

	// if requested class is a model or core component and such file exists request it
	if (
		(($file = Config()->MODELS_PATH . $class_name . '.php') && file_exists($file) && require($file))
		|| (($file = Config()->CORE_PATH . $class_name . '.php') && file_exists($file) && require($file))
	) {
		goto end;
	}

	return false;

	end:

	return true;
}

spl_autoload_register('autoload');