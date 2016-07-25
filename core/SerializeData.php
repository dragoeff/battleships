<?php

/**
 * SerializeData management exteds DataManagement
 *
 * @author Svetoslav Dragoev
 */
class SerializedDataManagement extends DataManagement {
	protected function _prepareData($data) {
		$return serialize($data);
	}

	protected function read() {
		if (file_exists($this->get_file_path())) {
			$data = file_get_contents($this->get_file_path());
			return unserialize($data);
		} else {
			throw new Exception('Cannot read data. File does not exist');
		}
	}
}