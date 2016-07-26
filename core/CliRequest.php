<?php

/**
 * Dispatch all cli/console request
 * 
 * @author Svetoslav Dragoev
 */
class CliRequest extends Dispatcher {
	/**
     * Turn persistant request on and off
     * Whether or not to expect stdin right after display
     *
     * @var boolean
     * @access public
     */
	public $is_persistant_request = true;

	/**
     * Default/initial parameters
     *
     * @var array
     * @access protected
     */
	protected $_default_params = [
		'controller' => 'console',
		'action' => 'start',
	];

	private $_controller;

	/**
	 * Issue the specified user request through Cli.
	 *
	 * @return array
	 * @access public
	 */
	public function set_params() {
		$params = ['request_parameters' => $GLOBALS['argv']];

		// define appropriate actin
		if(isset($GLOBALS['argv'][1])) {
			$params['action'] = 'show' == $GLOBALS['argv'][1] ? 'reveal' : 'shoot';
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

		!$this->_controller instanceof $controller_class && $this->_controller = new $controller_class(new CliView());
		
		if(!method_exists($this->_controller, $action)) {
			throw new Exception('Unknow action ' . $action);
		}
		
		$this->_controller->$action($this->_getRequestParameters());

		$this->is_persistant_request && $this->_stdin();
	}

	/**
	 * Listen for user input
	 *
	 * @return void
	 * @access public
	 */
	private function _stdin() {
		ob_flush();

		$stdin = fopen("php://stdin", "r");
		$result = trim(fgets($stdin));
		fclose($stdin);

		switch(trim($result)) {
			case 'show':
				$this->_setAction('reveal');
				break;
			case 'reset':
				$this->_controller = null;
				$this->_setAction('start');
				break;
			default:
				$this->_setAction('shoot');
				$this->_setRequestParams([
					'coordinates' => $result,
				]);
				break;
		}

		$this->dispatch();
	}
}