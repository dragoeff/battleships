<?php
/**
 * Application controller class
 *
 * @abstract
 * @author Svetoslav Dragoev
 */
abstract class GameController {

	protected $_board;

	function __construct() {
		$this->_board = new Board(Config()->SHIPS);
	}

	/**
	 * Start the game
	 * Generates a new board grid and random ship positions
	 */
	abstract public function start();

	/**
	 * Handle request and set message
	 */
	abstract public function shoot($params);

	/**
	 * Reveal ship positions
	 */
	abstract public function reveal();
}