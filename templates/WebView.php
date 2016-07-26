<?php

/**
 * Handle public web visualization
 * 
 * @author Svetoslav Dragoev
 */
class WebView extends View {
	use HtmlDecorator;

	/**
	 * Display game inteface.
	 *
	 * @return void
	 * @access public
	 */
	public function display() {
		$this->generate_grid();
		$this->render();
	}

}