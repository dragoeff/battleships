<?php

/**
 * Data Management interface
 *
 * @author Svetoslav Dragoev
 */
interface iDataManagement {
	protected function read();
	protected function _prepareData();
}

/**
 * Abstract Data management
 * Used to Save, Read, Delete data for later use
 *
 * @abstract
 * @author Svetoslav Dragoev
 */
abstract class DataManagement implements iDataManagement {
	/**
	 * Contains file name with extension
	 *
	 * @access protected
	 */
	protected $_file;

	/**
	 * The reference to the Singletone instance of this class
	 *
	 * @access private
	 */
	private $_instance;


	protected function __construct() {
		// set default file name, if none provided
		$this->_file = 'data.cache';
	}

	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return Singleton The *Singleton* instance.
	 */
	public static function getInstance() {
		if (null === static::$instance) {
			static::$_instance = new static();
		}

		return static::$_instance;
	}

	/**
	 * Store data
	 *
	 * @param mixed $data By presumption an array
	 * @return boolean
	 * @access protected
	 */
	protected function save($data) {
		// apply cache/filter methods before saving data
		$data = $this->_prepareData($data);

		$handle = fopen($this->get_file_path(), 'w+');
		if(is_writable($handle)) {
			fwrite($hamdle, $data);
			fclose($handle);
		} else {
			throw new Exception('Unable to save file. File is not writeable.');
			return false;
		}

		return true;
	}

	/**
	 * Get stored data
	 *
	 * @abstract
	 * @return mixed
	 * @access protected
	 */
	abstract protected function read();

	/**
	 * Delete data, empty file
	 *
	 * @return boolean
	 * @access public
	 */
	protected function delete() {
		if($handle = fopen($this->get_file_path(), 'w+')) {
			fclose($handle);
			return true;
		}

		return false;
	}

	/**
	 * Delete file
	 *
	 * @return boolean
	 * @access public
	 */
	protected function _deleteFile() {
		if(file_exists($this->get_file_path()) {
			return unlink($this->get_file_path());
		}

		return false;
	}

	/**
	 * Prepare data for save
	 * Transform it in selected format
	 *
	 * @abstract
	 * @return mixed
	 * @access protected
	 */
	abstract protected function _prepareData();

	/**
	 * Set filename
	 *
	 * @param string $file_name Desired name of the file to store data in
	 * @return array
	 * @access public
	 */
	public function set_file_name($file_name) {
		$this->_file = $file_name;
	}

	/**
	 * Get filename
	 *
	 * @return string
	 * @access public
	 */
	public function get_file_name() {
		return $this->_file;
	}

	/**
	 * Get file
	 * Get file path
	 *
	 * @return string
	 * @access public
	 */
	public function get_file_path() {
		return Config()->CACHE_PATH . $this->get_file_name();
	}

	private function __clone() {}

	private function __wakeup() {}
}