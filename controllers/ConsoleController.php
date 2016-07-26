<?php

/**
 * Handles console game
 * 
 * @author Svetoslav Dragoev
 */
class ConsoleController extends GameController {

	private $_view;

	/**
	 * Creates a console game object
	 * @param Map $map Takes a map object as init parameter, usually a new map
	 */
	
	public function __construct(iView $view) {
		$this->_view = $view;
		parent::__construct();
	}

	public function start() {
		$this->_board->build();
		$this->_view->set_data($this->_board->get_data()['public_grid']);
		$this->_view->display();
	}

	public function shoot($params) {
		$this->_board->shoot($params['coordinates'] ?: '');
		$this->_view->set_message($this->_board->get_message());

		$this->_view->set_data($this->_board->get_data()['public_grid']);
		$this->_view->display();
	}

	public function reveal() {
		$this->_view->set_data($this->_board->get_data()['military_grid']);
		$this->_view->display();
	}
}