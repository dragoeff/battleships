<?php

/**
 * Handle directioning of the ships on the grid
 *
 * @author Svetoslav Dragoev
 */
class Shipdirection implements iGame {

	protected
		$_grid,
		$_ship_size,
		$_ship_coordinates = [];

	/**
	 * Place a ship on the grid
	 *
	 * @uses _getShipDirection
	 * @uses _generateRandomCoordinates
	 * @uses check_ship_positioning
	 *
	 * @return void
 	 * @access public
	 */
	public function place_ship() {
		// reset ship coordinates as we are reusing this object
		$this->_ship_coordinates = [];

		// get ship direction - by x or y
		$direction = $this->_getShipDirection();
		// get ship start coordinates
		$coordinates = $this->_generateRandomCoordinates();
		// check if ship can fit/harbor at provided coordinates and direction
		$can_ship_harbor = $this->check_ship_positioning($coordinates, $direction);

		// if ship failed to harbor try again
		if(!$can_ship_harbor) {
			$this->place_ship();
		} else {
			for($i = 0; $i < $this->_ship_size; $i++) {
				// set second grid having ships on it
				$this->_grid[$coordinates['x']][$coordinates['y']] = iGame::SHIP;

				// add ship coordinates: 'A4', 'A5', 'A6' ...
				$this->_ship_coordinates[] = $coordinates['x'] . $coordinates['y'];

				// set next cell
				$coordinates[$direction]++;
			}
		}
	}

	/**
	 * Return grid
	 *
	 * @return array
	 * @access public
	 */
	public function get_grid() {
		return $this->_grid;
	}

	/**
	 * Set grid
	 *
	 * @params array
	 * @return void
	 * @access public
	 */
	public function set_grid($grid) {
		$this->_grid = $grid;
	}

	/**
	 * Set ship size
	 *
	 * @params integer
	 * @return void
	 * @access public
	 */
	public function set_ship_size($size) {
		$this->_ship_size = (int)$size;
	}
	
	/**
	 * Return ship coordinates.
	 *
	 * @return array
	 * @access public
	 */
	public function get_ship_coordinates() {
		return $this->_ship_coordinates;
	}

	/**
	 * Genreate random positon of the ship within the margins of the grid
	 *
	 * @return array
	 * @access protected
	 */
	protected function _generateRandomCoordinates() {
		$max = iGame::GRID_SIZE - $this->_ship_size;
		
		return [
			'x' => mt_rand(0, $max),
			'y' => mt_rand(0, $max),
		];
	}

	/**
	 * Determine direction of ship - horizontal or vertical
	 *
	 * @return string
	 * @access protected
	 */
	protected function _getShipDirection() {
		return (mt_rand(0, 1) == 1 ? 'x' : 'y');
	}


	/**
	 * Check if random coordinates are in place
	 *
	 * @param array $coordinates - [x => 3, y => 2]
	 * @param string $direction - either x or y
	 * 
	 * @return boolean
	 * @access public
	 */
	public function check_ship_positioning(array $coordinates, $direction) {
		$result = true;
		$this->_ship_coordinates = [];
		
		for($i = 0; $i < $this->_ship_size; $i ++) {
			if(!isset($this->_grid[$coordinates['x']][$coordinates['y']]) || $this->_grid[$coordinates['x']][$coordinates['y']] != iGame::BLANK) {
				$result = false;
				break;
			}

			$coordinates[$direction]++;
		}

		return $result;
	}
}