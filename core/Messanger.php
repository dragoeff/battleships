<?php

/**
 * Messanger trait, provides basic messaging functionality
 *
 * @author Svetoslav Dragoev
 */
trait Messanger {

	/**
	 * Generated message
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_message = '';

	/**
	 * New line separator
	 *
	 * @var string
	 * @access protected
	 */
	protected $_new_line;

	/**
	 * Predefined list of messages
	 *
	 * @var array
	 * @access protected
	 */
	private $_message_templates = [
		'game_over' => "Well done! You completed the game in %d shots. Only Chuck Norris would'be done better. Press start to play again.",
		'already_played' => "You already tried there",
		'invalid_coordinates' => "Hmmm. With such shooting you won't get far",
		'shot_miss' => "You missed",
		'shot_hit' => "Hit! Right on target",
		'sunk_ship' => "HIT!!! Congratulations you sunk this ship",
	];

	/**
	 * Set type of new line depending on environment
	 */
	public function __construct() {
		$this->_new_line = 'cli' == Config()->ENVIRONMENT ? "\n\r" : "<br />";
	}

	/**
	 * Generate message output
	 *
	 * @param  mixed $shot integer[2-3]|boolean|'hacker'
	 * @return string      The produced message
	 */
	public function generate_messsage($key, $message_data = array()) {
		if(!isset($this->_message_templates[$key])) {
			$this->_message = '';
		} else {
			$this->_message = !empty($message_data) ? (
				is_array($message_data) ? vsprintf($this->_message_templates[$key], $message_data) : sprintf($this->_message_templates[$key], $message_data)
			) : $this->_message_templates[$key];
		}
	}

	public function get_new_line() {
		return $this->_new_line;
	}

	/**
	 * Get _message
	 *
	 * @return string
	 * @access public
	 */
	public function get_message() {
		return $this->_message;
	}
}