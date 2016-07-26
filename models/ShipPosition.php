<?php

/**
 * Handle directioning of the ships on the grid
 *
 * @author Svetoslav Dragoev
 */
class ShipPosition implements iGame {

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

		// if we can't fit the ship from the selected coordinates render again
		if(!$this->_checkCoordinates($coordinates, $direction)) {
			$this->place_ship();
		} else {
			// update grid and ship coordinates
			for($i = 0; $i < $this->_ship_size; $i++) {
				// set second grid/military with the ships visible on it
				// Grid matrix is saved in the form [Y, [X, X,...]] because we first generate the row and then he columns
				$this->_grid[$coordinates['y']][$coordinates['x']] = iGame::SHIP;

				// add ship coordinates: 'A4', 'A5', 'A6' ...
				$this->_ship_coordinates[] = $coordinates['y'] . $coordinates['x'];

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
			'y' => mt_rand(0, $max),
			'x' => mt_rand(0, $max),
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
	 * Check if random coordinates are blank
	 *
	 * @param array $coordinates - [x => 3, y => 2]
	 * @param string $direction - either x or y
	 * 
	 * @return boolean
	 * @access public
	 */
	protected function _checkCoordinates(array $coordinates, $direction) {
		if(!empty($this->_grid)) {
			// set range from selected coordinate until shipsize length
			$ship_size_range = range($coordinates[$direction], $coordinates[$direction] + $this->_ship_size - 1);

			// check for clear water / all cells are blank
			$ship_size_range = array_filter($ship_size_range, function($range_value) use($direction, $coordinates) {
				$x = $y = $range_value;
				'x' === $direction ? $y = $coordinates['y'] : $x = $coordinates['x'];

				// is slot blank?
				return iGame::BLANK == $this->_grid[$y][$x];
			});

			// if all slots in the ship range were blank then they should be the same number as ship size
			return count($ship_size_range) == $this->_ship_size;
		}

		return false;
	}
}