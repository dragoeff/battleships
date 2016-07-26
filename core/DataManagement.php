<?php

/**
 * Data Management interface
 *
 * @author Svetoslav Dragoev
 */
interface iDataManagement {
	public function read();
	public function prepare_data($data);
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
	 * @access protected
	 */
	static protected $_instance;


	protected function __construct() {
		// set default file name, if none provided
		$this->_file = 'data_' . md5(is_cli() ? gethostname() : $_SERVER['REMOTE_ADDR']) . '.cache';
	}

	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return Singleton The *Singleton* instance.
	 */
	public static function getInstance() {
		if (null === static::$_instance) {
			static::$_instance = new static();
		}

		return static::$_instance;
	}

	/**
	 * Store data
	 *
	 * @param mixed $data By presumption an array
	 * @return boolean
	 * @access public
	 */
	public function save($data) {
		// apply cache/filter methods before saving data
		$data = $this->prepare_data($data);

		$handle = fopen($this->get_file_path(), 'w+');
		if(is_writable($this->get_file_path())) {
			fwrite($handle, $data);
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
	 * @access public
	 */
	abstract public function read();

	/**
	 * Delete data, empty file
	 *
	 * @return boolean
	 * @access public
	 */
	public function delete() {
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
		if(file_exists($this->get_file_path())) {
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
	abstract public function prepare_data($data);

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
		return Config()->CACHE_PATH . DS . $this->get_file_name();
	}

	private function __clone() {}

	private function __wakeup() {}
}