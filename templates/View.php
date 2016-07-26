<?php

/**
 * class View
 *
 * @abstract
 * @author Svetoslav Dragoev
 */
abstract class View implements iView {
	/**
	 * Contains information about each cell
	 *
	 * @var array
	 * @access protected
	 */
	protected $_data;

	/**
	 * Contains file name with extension
	 *
	 * @var array
	 * @access protected
	 */
	protected $_grid;

	/**
	 * Contains message string
	 *
	 * @var string
	 * @access protected
	 */
	protected $_message;

	/**
	 * Pass data to display
	 *
	 * @params array
	 * @return void
	 * @access public
	 */
	public function set_data($data){
		$this->_data = $data;
	}

	/**
	 * Pass messages to display
	 *
	 * @params string
	 * @return void
	 * @access public
	 */
	public function set_message($message) {
		$this->_message = $message;
	}

	/**
	 * Display game inteface.
	 *
	 * @abstract
	 * @return void
	 * @access public
	 */
	abstract function display();

	/**
	* Prepare user interface to begin a game
	*
	* @return void
	* @access public
	*/
	public function generate_grid() {
		$letters = range('A', 'Z');
		$total_cells = count($this->_data);
		
		$this->_grid .= "\r\n" . $this->_message . "\r\n";
		$this->_grid .= "\r\n";

		for($row = 0; $row < $total_cells + 1; $row ++) {
			$this->_grid .= " " . (($row > 0) ? $row : "");
		}

		$this->_grid .= "\r\n";
		
		for($row = 0; $row < $total_cells; $row ++) {
			$this->_grid .= $letters[$row];
			
			for($col = 0; $col < $total_cells; $col ++) {
				$this->_grid .= " " . (isset($this->_data[$row][$col]) ? $this->_data[$row][$col] : "  ");
			}

			$this->_grid .= "\r\n";
		}
	}
}