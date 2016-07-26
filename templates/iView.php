<?php

/**
 * View interface
 *
 * @author Svetoslav Dragoev
 */
interface iView {
	public function set_data($data);
	public function set_message($message);
	public function display();
}