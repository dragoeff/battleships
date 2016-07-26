<?php

/**
 * Dispatch web requests
 * 
 * @author Svetoslav Dragoev
 */
class WebRequest extends Dispatcher {
	protected $_default_params = [
		'controller' => 'web',
		'action' => 'start',
	];
	
	/**
	 * Get request parameters
	 *
	 * @return array
	 * @access public
	 */
	public function set_params() {
		$params = ['request_parameters' => $_POST];

		// define appropriate actin
		if(isset($_POST['coordinates'])) {
			$params['action'] = 'show' == $_POST['coordinates'] ? 'reveal' : 'shoot';
		}

		return array_merge($this->_default_params, $params);
	}
	
	/**
	 * Invoke required action
	 *
	 * @return void
	 * @access public
	 */
	public function dispatch() {
		$controller_class = $this->_getController();
		$action = $this->_getAction();
		
		$controller = new $controller_class(new WebView());
		
		if(!method_exists($controller, $action)) {
			throw new Exception('Unknow action ' . $action);
		}
		
		$controller->$action($this->_getRequestParameters());
	}
}