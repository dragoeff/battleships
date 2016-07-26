<?php

/**
 * Handle shots
 *
 * @author Svetoslav Dragoev
 */
trait Shot {
	/**
     * Coordinates provided by user
     *
     * @var string
     * @access protected
     */
	protected $_shot_coordinates;

	/**
     * Coordinates provided by user translated into array [x, y]
     * @example 'B4' would be presented as <code>['x' => 1, 'y' => 3]</code>
     *
     * @var array
     * @access protected
     */
	protected $_shot_coordinates_array;

	/**
     * Coordinates provided by user translated into string 03
     * @example 'B4' would be presented as '13'
     *
     * @var array
     * @access protected
     */
	protected $_shot_coordinates_string;

	/**
	 * Get user hit try count
	 *
	 * @return integer
	 * @access protected
	 */
	protected function _getShotAttempts() {
		return count($this->_data['user_shots']);
	}

	 /**
	* Check there is a ship on that position
	*
	* @return boolean
	* @access protected
	*/
	protected function _isHit() {
		$grid = $this->_data['military_grid'];
		return $grid[$this->_shot_coordinates_array['y']][$this->_shot_coordinates_array['x']] == iGame::SHIP;
	}

	/**
	 * Check if coordinates have already been played
	 *
	 * @return boolean
	 * @access protected
	 */
	protected function _isAlreadyPlayed() {
		return (!empty($this->_data['user_shots'])) ? in_array($this->_shot_coordinates, $this->_data['user_shots']) : false;
	}

	/**
	 * Set mark on this coordinate when grid is rebuild
	 *
	 * @return void
	 * @access protected
	 */
	protected function _setHitStatus($hit_status) {
		$this->_data['public_grid'][$this->_shot_coordinates_array['y']][$this->_shot_coordinates_array['x']] = $hit_status;
		$this->_data_manager->save($this->_data);
	}

	/**
	 * Save user hits
	 *
	 * @return void
	 * @access protected
	 */
	protected function _saveUserHits() {
		$this->_data['user_shots'][] = $this->_shot_coordinates;
		$this->_data_manager->save($this->_data);
	}

	/**
	 * Check for game status
	 *
	 * @return boolean
	 * @access protected
	 */
	protected function _isGameOver() {
		return empty($this->_data['ships']);
	}

	/**
	 * Check whether any of the ships has been hit or not
	 *
	 * @return boolean
	 * @access protected
	 */
	protected function _isShipSunk() {
		$result = false;
		$ships = $this->_data['ships'];
		$total_ships = count($ships);

		for ($i=0; $i < $total_ships; $i++) {
			if($ships[$i]->is_hit($this->_shot_coordinates_string)) {
				// mark hit upon ship size
				$ships[$i]->remove_hit_coordinate($this->_shot_coordinates_string);

				// remove ship from the list if all of his slots along ship size are hit
				if($ships[$i]->is_sunk()) {
					unset($ships[$i]);
					$result = true;
				}

				break;
			}
		}

		// re-order ships in order to maintain simple index and save updated data
		sort($ships);
		$this->_data['ships'] = $ships;
		
		$this->_data_manager->save($this->_data);

		return $result;
	}

	/**
	 * Validate coordinates
	 * Check if provided coordinates are in the format ([a-{grid_size_letter}])([0-{grid_size}-1]|{grid_size})
	 * Where {grid_size} is predefined max number of slots and {grid_size_letter} is the ASCII char representation of that number
	 *
	 * @return boolean
	 * @access protected
	 */
	protected function _validateCoordinates() {
		return !empty($this->_shot_coordinates) && (bool)preg_match('/^([' . chr(iGame::ASCII_A) . '-' . chr(iGame::ASCII_A + iGame::GRID_SIZE - 1) . '])([1-' . (iGame::GRID_SIZE - 1) . ']|' . iGame::GRID_SIZE . ')$/i', $this->_shot_coordinates);
	}
}