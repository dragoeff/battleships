<?php

/**
 * Handles standard browser game
 * 
 * @author Svetoslav Dragoev
 */
class WebController extends GameController {

	private $_view;

	function __construct(iView $view) {
		$this->_view = $view;
		parent::__construct();
	}

	/**
	 * Start new game
	 * Generates a new board grid and place ships
	 * 
	 * @access public
	 */
	public function start() {
		$this->_board->build();

		$this->_view->set_data($this->_board->get_data('public_grid'));
		$this->_view->display();
	}

	/**
	 * Handle shoot request and set message
	 * 
	 * @param array $params - query params
	 * @access public
	 */
	public function shoot($params) {
		// load data before loading action
		$this->_board->reload_persistant_data();

		$this->_board->shoot(!empty($params['coordinates']) ? $params['coordinates'] : null);
		$this->_view->set_message($this->_board->get_message());

		$this->_view->set_data($this->_board->reload_persistant_data('public_grid'));
		$this->_view->display();
	}

	/**
	 * Reveal all ship positions
	 * 
	 * @access public
	 */
	public function reveal() {
		$this->_board->reload_persistant_data();
		$this->_view->set_data($this->_board->get_data('military_grid'));
		$this->_view->display();
	}
}