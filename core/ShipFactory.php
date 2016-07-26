<?php

/**
 * Implementation of parameterized factory pattern
 *
 */
class ShipFactory {
	public static final function build($type) {
		switch ($type) {
			case 'battleship':
			case 'destroyer':
				$class = ucwords($type);
				break;
			default:
				throw new \Exception('Invalid Ship type: ' . $type);
				break;
		}

		!class_exists($class) && include(Config()->CORE_PATH . $class . '.php');

        return new $class();
	}
}