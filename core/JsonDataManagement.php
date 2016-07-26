<?php

/**
 * JsonData management exteds DataManagement
 *
 * @author Svetoslav Dragoev
 */
class JsonDataManagement extends DataManagement {
	public function prepare_data($data) {
		return json_encode($data);
	}

	public function read() {
		if (file_exists($this->get_file_path())) {
			$data = file_get_contents($this->get_file_path());
			return json_decode($data);
		} else {
			throw new Exception('Cannot read data. File does not exist');
		}
	}
}