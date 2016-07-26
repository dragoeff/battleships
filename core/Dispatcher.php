<?php

/**
 * Dispatch requests
 * Dispatcher takes the URL or Cli information, parse it for paramters and tells the involved controllers what to do
 * 
 * @author Svetoslav Dragoev
 */
abstract class Dispatcher {
	protected $_params = [];

	public function __construct() {
		$this->_params = $this->set_params();
	}
	
	/**
	 * Collect parameters provided
	 * 
	 * @abstract
	 * @return void
	 * @access public
	 */
	abstract public function set_params();
	
	/**
	 * Dispatches the request, creating appropriate models and controllers.
	 * 
	 * @abstract
	 * @return void
	 * @access public
	 */
	abstract public function dispatch();

	/**
	 * Get controller from request.
	 *
	 * @return void
	 * @access protected
	 */
	protected function _getController() {
		$class = ucwords($this->_params['controller']) . 'Controller';
		
		if(!class_exists($class)) {
			throw new Exception('Unknown controller ' . $class);
		}
		
		return $class;
	}

	/**
	 * Get action from request.
	 *
	 * @return mixed String or Null if no 
	 * @access protected
	 */
	protected function _getAction() {
		return !empty($this->_params['action']) ? $this->_params['action'] : null;
	}

	/**
	 * Get action from request
	 *
	 * @return mixed String or Null if no 
	 * @access protected
	 */
	protected function _getRequestParameters() {
		return !empty($this->_params['request_parameters']) ? $this->_params['request_parameters'] : [];
	}

	/**
	 * Set request params
	 *
	 * @param array $params
	 * @return void
	 * @access protected
	 */
	protected function _setRequestParams(array $params) {
		$this->_params['request_parameters'] = $params;
	}

	/**
	 * Set action
	 *
	 * @param string $action
	 * @return void
	 * @access protected
	 */
	protected function _setAction($action) {
		$this->_params['action'] = $action;
	}
}