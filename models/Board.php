<?php

namespace Battleships\Models;

use Battleships\Core\Ship;
use Battleships\Core\ShipFactory;

/**
 * class Board
 * Represents game board grid style
 *
 * @author Svetoslav Dragoev
 */
class Board extends Shot implements iGame {
	protected
		$_message = '',
		$_total_ships = 0;

	private
		$_grid = [],
		$_ships = [],
		$_data = [
			'user_shots' => 0,
			'grid' => [],
			'ships_grid' => [],
		],
		$_vertical_grid_range = [];
		$_horizontal_grid_range = [];
	
	public function __construct(array $ships) {		
		foreach ($ships as $ship_type => $number_of_ships) {
			while(0 < $number_of_ships) {
				$this->_ships[] = ShipFactory::build($ship_type);
				$number_of_ships--;
			}
		}

		$this->_total_ships = count($this->_ships);
	}

	/**
	 * Build board
	 * Draw grid and place ships
	 *
	 * @access public
	 */
	public function build() {
		foreach ($this->get_horizontal_grid_range() as $horizontal_key => $letter) {
			foreach ($this->get_vertical_grid_range() as $vertical_key => $number) {
				$this->_grid[$horizontal_key][$vertical_key] = iGame::BLANK;
			}
		}

		$this->_data['grid'] = $this->_grid;
		$this->_placeShips();
	}

	/**
	 * Place ships on the generated grid
	 */
	protected function _placeShips() {
		$ship_position = new ShipPosition();
		$ship_position->set_grid($this->_grid);

		foreach ($this->_ships as $ship) {
			$ship_position->set_ship_size($ship->get_size());
			$ship_position->place_ship();

			// set Ship object the coordinates at which we randomly positioned him over the grid
			$ship->set_coordinate($ship_position->get_ship_coordinates());
		}

		$this->_data['ships'] = $this->_ships;
		$this->_data['ships_grid'] = $ship_position->get_grid();
	}

	/**
	 * Shoot on target
	 * 
	 * @return void
	 * @access public
	 */
	public function shoot($shot_coordinates) {
		$this->_shot_coordinates = !empty($shot_coordinates['xy']) ? $shot_coordinates['xy'] : $shot_coordinates;

		if($this->validateCoordinates()) {
			$this->_converted_coordinate_array = Convertor::ToArray($this->_shot_coordinates);
			$this->_ship_position = Convertor::ToString($this->_shot_coordinates);
			$this->_data = $this->saveData->get();
			
			if($this->_isAlreadyPlayed()) {
				$this->message = 'Those coordinates have already been tried.';
			} else {
				$this->_saveUserHits($this->_shot_coordinates);

				if(!$this->_isHit($this->_shot_coordinates)) {
					$this->_setHitStatus(iGame::MISS);
					$this->message = 'That is a miss';
				} else {
					$this->message = 'Hit! Right on target.';
					$this->_setHitStatus(iGame::HIT);
					
					if($this->_isShipSunk()) {
						$this->message = 'HIT!!! Congratulations you sunk this ship.';
					}
					
					if($this->_isGameOver()) {
						$this->message = 'Well done! You completed the game in ' . $this->_getShotAttempts() . ' shots.';
					}
				}
			}
		} else {
			$this->message = 'Those coordinates wont work. Please try again.';
		}
	}

	/**
	 * Get all grids
	 *
	 * @return array
	 */
	public function get_data() {
		return $this->_data;
	}

	/**
	 * Get grid size for this board
	 *
	 * @return integer
	 */
	public function get_grid_size() {
		return iGame::GRID_SIZE;
	}

	/**
	 * Converts a string literal into an integer one
	 *
	 * @example $char = a => ord(a) - 97 = 97-97 = 0
	 *
	 * @param  string $char A char in the scope of A-J
	 * @return integer      The corresponding integer value
	 */
	public function translate_char($char) {
		return ord(strtolower($char)) - iGame::ASCII_A;
	}

	/**
	 * Converts a integer into a string literal
	 *
	 * @example $cooardinate = 1 => char(1 + 97 - 1) = ASCII(97)
	 *
	 * @param  integer $char An integer in the scope of 0-9
	 * @return integer      The corresponding character value
	 */
	public function translate_number($coordinate) {
		return char((int)$coordinate + iGame::ASCII_A - 1);
	}

	/**
	 * Get message
	 *
	 * @return string
	 * @access public
	 */
	public function get_message() {
		return $this->_message;
	}

}