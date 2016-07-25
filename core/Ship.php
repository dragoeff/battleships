<?php

namespace Battleships\Core;

/**
 * Abstract Ship class
 *
 * @abstract
 * @author Svetoslav Dragoev
 */
abstract class Ship {
	/**
	 * Define ship size, number of slots it takes
	 *
	 * @access protected
	 */
	protected $_size;

	/**
	 * Define ship name, for public presentation
	 *
	 * @access protected
	 */
	protected $_name;

	/**
	 * Define ship name, for public presentation
	 *
	 * @access protected
	 */
	protected $_coordinates = array();

	/**
	 * Get ship length
	 *
	 * @return integer
	 * @access public
	 */
	public function get_size() {
		return $this->_size;
	}
	
	/**
	 * Get ship name
	 *
	 * @return string
	 * @access public
	 */
	public function get_name() {
		return $this->_name;
	}

	/**
	 * Set ship coordinates
	 *
	 * @params array
	 * @return void
	 * @access public
	 */
	public function set_coordinate(array $xy) {
		$this->_coordinates = $xy;
	}

	/**
	 * Check if any part of the ship is hit
	 *
	 * @params string 
	 * @example $xy = 'A9';
	 *
	 * @return boolean
	 * @access public
	 */
	public function is_hit($xy) {
		return in_array($xy, $this->_coordinates);
	}
	
	/**
	 * Check if ship is sunk
	 *
	 * @return boolean
	 * @access public
	 */
	public function is_sunk() {
		return empty($this->_coordinates);
	}
	
   	/**
   	 * Register a hit
	 * Remove hit coordinate from the ship
	 *
	 * @params string
	 * @return void
	 * @access public
	*/
	public function remove_hit_coordinate($xy) {
		if(($key = array_search($xy, $this->_coordinates)) !== false) {
			unset($this->_coordinates[$key]);
		}
	}
}