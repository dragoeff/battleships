<?php

/**
 * Handle shots
 *
 * @author Svetoslav Dragoev
 */
class Shot {
	protected
		$_shot_coordinates;
		$_converted_coordinate_array;
		$_coordinate_string;
		$_ship_position;

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
		$grid = $this->_data_manager->read()['shipField'];

		if(isset($grid[$this->_converted_coordinate_array['x']][$this->_converted_coordinate_array['y']])){
			if($grid[$this->_converted_coordinate_array['x']][$this->_converted_coordinate_array['y']] == iGame::SHIP){
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if coordinates have already been played
	 *
	 * @return boolean
	 * @access protected
	 */
	protected function _isAlreadyPlayed() {
		return (isset($this->_data['user_shots'])) ? in_array($this->_shot_coordinates, $this->_data['user_shots']) : false;
	}

	/**
	 * Set mark on this coordinate when grid is rebuild
	 *
	 * @return void
	 * @access protected
	 */
	protected function _setHitStatus($hit_status) {
		$this->_data['grid'][$this->_converted_coordinate_array['x']][$this->_converted_coordinate_array['y']] = $hit_status;
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
		return empty($this->_data_manager->read()['ships']);
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

		for ($i=0; $i < count($ships); $i++) {
			if($ships[$i]->is_hit($this->_ship_position)) {
				$ships[$i]->remove_hit_coordinate($this->_ship_position);

				if($ships[$i]->is_sunk()) {
					unset($ships[$i]);
					$result = true;
				}

				break;
			}
		}

		$this->_data['ships'] = $ships;
		$this->_data_manager->save($this->_data);

		return $result;
	}

	/**
	 * Validate coordinates
	 *
	 * @return boolean
	 * @access protected
	 */
	protected function _validateCoordinates() {
		return (bool)preg_match('/^([' . char(iGame::ASCII_A) . '-' . char(iGame::ASCII_A + iGame::GRID_SIZE - 1) . '])([1-' . iGame::GRID_SIZE - 1 . ']|' . iGame::GRID_SIZE . ')$/i', $this->_shot_coordinates);
	}
}