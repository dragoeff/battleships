<?php
/*
 * Provide Game interface for console users.
 */
class CliView extends View {
   /**
	* Prepare user interface to begin a game
	*
	* @return void
	* @access public
	*/
	public function generate_board() {
		echo gethostname();
		echo '---------------= HELP =-------------------' . "\r\n";
		echo 'Available commands: show, reset   ' . "\r\n";
		echo '- `show` would reveal the board   ' . "\r\n";
		echo '- `reset` would start a new game  ' . "\r\n";
		echo '- Cooardinate A4 would make a shot' . "\r\n";
		echo '------------------------------------------' . "\r\n";
		
		$this->generate_grid();
	}
	
	/**
	 * Display game inteface
	 *
	 * @return void
	 * @access public
	 */
	public function display() {
		passthru(DIRECTORY_SEPARATOR == '/' ? 'clear' : 'cls');

		$this->generate_board();

		echo $this->_grid;
		echo 'Please enter coordinates (row, col), e.g. A5:';
	}
}