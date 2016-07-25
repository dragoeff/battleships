<?php
/**
 * Application controller class
 *
 * @abstract
 * @author Svetoslav Dragoev
 */
abstract class GameController {
	use Messagener;

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
	abstract public function shoot();

	/**
	 * Reveal ship positions
	 */
	abstract public function reveal();
}