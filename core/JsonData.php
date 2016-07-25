<?php

/**
 * JsonData management exteds DataManagement
 *
 * @author Svetoslav Dragoev
 */
class JsonDataManagement extends DataManagement {
	protected function _prepareData($data) {
		$return json_encode($data);
	}

	protected function read() {
		if (file_exists($this->get_file_path())) {
			$data = file_get_contents($this->get_file_path());
			return json_decode($data);
		} else {
			throw new Exception('Cannot read data. File does not exist');
		}
	}
}