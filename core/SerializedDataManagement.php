<?php

/**
 * SerializeData management exteds DataManagement
 *
 * @author Svetoslav Dragoev
 */
class SerializedDataManagement extends DataManagement {
	public function prepare_data($data) {
		return serialize($data);
	}

	public function read() {
		if (file_exists($this->get_file_path())) {
			$data = file_get_contents($this->get_file_path());
			return unserialize($data);
		} else {
			throw new Exception('Cannot read data. File does not exist');
		}
	}
}