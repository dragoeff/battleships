<?php

/**
 * Handles standard browser action
 */
class WebController extends GameController {
	use Messagener;

	private $_view;

	function __construct() {
		$this->_view = new View();
	}

	/**
	 * Start the game
	 * Generates a new board grid and random ship positions
	 */
	public function start() {
		$this->_board->build();
		$this->_view->set_data();
		$this->_view->display();
	}

	/**
	 * Handle request and set message
	 */
	public function shoot() {
		$this->_board->shoot();
		$this->_view->set_message($this->_board->get_message());
		$this->_view->set_data();
		$this->_view->display();
	}

	/**
	 * Reveal ship positions
	 */
	public function reveal() {
		$this->_view->set_data($this->_board->get_data()['ships_grid']);
		$this->_view->display();
	}
}