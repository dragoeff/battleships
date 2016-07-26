<?php

/**
 * class Board
 * Represents game board grid style
 *
 * @author Svetoslav Dragoev
 */
class Board implements iGame {
	use Shot;
	use CoordinatesTranslator;
	use Messanger;
	
	/**
	 * Keep log of the number of ships generated
	 *
	 * @var integer
	 * @access protected
	 */
	protected $_total_ships = 0;

	/**
	 * Board grid
	 *
	 * @var array
	 * @access private
	 */
	private	$_grid = [];

	/**
	 * Ship objects
	 *
	 * @var array of object
	 * @access private
	 */
	private $_ships = [];

	/**
	 * Persistant data
	 *
	 * @var array
	 * @access private
	 */
	private $_data = [
		'user_shots' => [],
		'public_grid' => [],
		'military_grid' => [],
	];
	
	/**
	 * Data management instance
	 *
	 * @var object
	 * @access private
	 */
	private $_data_manager;

	public function __construct(array $ships) {
		foreach ($ships as $ship_type => $number_of_ships) {
			while(0 < $number_of_ships) {
				$this->_ships[] = ShipFactory::build($ship_type);
				$number_of_ships--;
			}
		}

		$this->_total_ships = count($this->_ships);
		$this->_data_manager = SerializedDataManagement::getInstance();
	}

	/**
	 * Build board
	 * Draw grid and place ships
	 *
	 * @access public
	 */
	public function build() {
		for($y = 0; $y < iGame::GRID_SIZE; $y++) {
			for($x = 0; $x < iGame::GRID_SIZE; $x++) {
				$this->_grid[$y][$x] = iGame::BLANK;
			}
		}

		// init/reset data
		$this->_message = '';
		$this->_data['user_shots'] = [];
		$this->_data['public_grid'] = $this->_grid;
		$this->_data_manager->save($this->_data);

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

		// keep record for each ship so we can later register hits and validate if ship has sink or not
		$this->_data['ships'] = $this->_ships;

		// record grid with ships presented, don't let spies lay hands over it
		$this->_data['military_grid'] = $ship_position->get_grid();

		$this->_data_manager->save($this->_data);
	}

	/**
	 * Shoot on target
	 *
	 * @return void
	 * @access public
	 */
	public function shoot($shot_coordinates = null) {
		$this->_shot_coordinates = $shot_coordinates;


		if($this->_isGameOver()) {
			$this->generate_messsage('game_over', $this->_getShotAttempts());
			goto end;
		}

		if($this->_isAlreadyPlayed()) {
			$this->generate_messsage('already_played');
			goto end;
		}

		if(!$this->_validateCoordinates()) {
			$this->generate_messsage('invalid_coordinates');
			goto end;
		}

		// translated shot coordinates into array [x, y]
		$this->_shot_coordinates_array = $this->coordinates_to_array($this->_shot_coordinates);
		// translated shot coordinates into string 13
		$this->_shot_coordinates_string = $this->coordinates_to_string($this->_shot_coordinates);

		$this->_saveUserHits($this->_shot_coordinates);

		if(!$this->_isHit($this->_shot_coordinates)) {
			$this->_setHitStatus(iGame::MISS);
			$this->generate_messsage('shot_miss');
		} else {
			$this->generate_messsage('shot_hit');
			$this->_setHitStatus(iGame::HIT);

			if($this->_isShipSunk()) {
				$this->generate_messsage('sunk_ship');
				$this->_isGameOver() && $this->_message .= $this->get_new_line() . $this->generate_messsage('game_over', $this->_getShotAttempts());
			}

		}

		end:;
	}

	/**
	 * Get persistant data
	 *
	 * @param string $key any of the persistant data keys which data we search - public_grid, military_grid, ships, user_shots
	 * @return array
	 */
	public function get_data($key = null) {
		if($key) {
			return !empty($this->_data[$key]) ? $this->_data[$key] : null;
		}

		return $this->_data;
	}

	/**
	 * Set persistant data
	 *
	 * @return array
	 */
	public function set_data($data) {
		$this->_data = $data;
	}

	/**
	 * Reload persistant data from storage
	 *
	 * @param string $key any of the persistant data keys which data we search
	 * @return array
	 */
	public function reload_persistant_data($key = null) {
		$this->set_data($this->_data_manager->read());
		return $this->get_data($key);
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
	 * Return declared Data manager instance
	 *
	 * @return object
	 * @access public
	 */
	public function get_data_manager() {
		return $this->_data_manager;
	}

}