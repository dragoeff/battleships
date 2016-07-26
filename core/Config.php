<?php

/**
 * Config class
 * All settings and configuration for the application
 *
 * @final
 * @author Svetoslav Dragoev
 */
final class DefaultConfig {
	/******************************************************************
	 * PUBLIC and LOCAL PATHS
	 ******************************************************************/

	/**
	 * Local path of the application on the server.
	 *
	 * @var string
	 * @access private
	 */
	private $ROOT_PATH;

	/**
	 * Local path of the freamework core on the server.
	 *
	 * @var string
	 * @access private
	 */
	private $CORE_PATH;

	/**
	 * Local path of configuration.
	 *
	 * @var string
	 * @access private
	 */
	private $CONFIG_PATH;

	/**
	 * Local path of the application controllers on the server.
	 *
	 * @var string
	 * @access private
	 */
	private $CONTROLLERS_PATH;

	/**
	 * Local path of the application models on the server.
	 *
	 * @var string
	 * @access private
	 */
	private $MODELS_PATH;

	/**
	 * Local path of the application cache on the server.
	 *
	 * @var string
	 * @access private
	 */
	private $CACHE_PATH;

	/******************************************************************
	 * TEMPLATE SETTINGS
	 ******************************************************************/

	/**
	 * Local path of the application templates on the server.
	 *
	 * @var string
	 * @access private
	 */
	private $VIEWS_PATH;

	/**
	 * Local path of the application layouts on the server.
	 *
	 * @var string
	 * @access private
	 */
	private $LAYOUTS_PATH;

	/**
	 * Public path of the the cookie.
	 *
	 * @var string
	 * @access private
	 */
	private $COOKIE_PATH;

	/**
	 * Public root url of the application.
	 *
	 * @var string
	 * @access private
	 */
	private $PUBLIC_URL;

	/**
	 * Reference to the current instance of the Config object
	 *
	 * @var object
	 * @access private
	 */
	private static $instance = null;

	/**
	 * Set all variables
	 *
	 * @access private
	 */
	private function __construct() {
		$this->ROOT_PATH = dirname(dirname(__FILE__)) . DS;
		$this->CORE_PATH = $this->ROOT_PATH . 'core' . DS;
		$this->CONFIG_PATH = $this->ROOT_PATH . 'config' . DS;
		$this->CONTROLLERS_PATH = $this->ROOT_PATH . 'controllers' . DS;
		$this->MODELS_PATH = $this->ROOT_PATH . 'models' . DS;
		$this->LAYOUTS_PATH = $this->ROOT_PATH . 'templates' . DS . 'layouts' . DS;
		$this->VIEWS_PATH = $this->ROOT_PATH . 'templates';
		$this->CACHE_PATH = $this->ROOT_PATH . 'cache';

		$real_path = rtrim(realpath($_SERVER['DOCUMENT_ROOT']), DS);
		if (basename($_SERVER['SCRIPT_NAME']) == 'index.php') {
			$this->COOKIE_PATH = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\') . '/';
		} else {
			$this->COOKIE_PATH = str_replace($real_path, '', $this->ROOT_PATH);
		}

		$this->PUBLIC_URL = $this->COOKIE_PATH;
		substr($real_path, -7) !== DS . 'public' && ($this->PUBLIC_URL .= 'public/');

		// we only use production for this project
		$this->ENVIRONMENT = 'production';

		if (php_sapi_name() == "cli") {
			$this->ENVIRONMENT = 'cli';
			$file = $this->CONFIG_PATH . 'cli.php';
		} else {
			$file = $this->CONFIG_PATH . $this->ENVIRONMENT . '.php';
		}

		// include user defined configurations
		require($file);

		// set user defined configurations
		$vars = get_object_vars(new Config($this));
		foreach ($vars as $var => $value) {
			$this->$var = $value;
		}

		return true;
	}

	/**
	 * Returns an instance of the config object
	 *
	 * @static
	 * @final
	 * @return Config
	 *
	 * @uses Config::$ROOT_PATH
	 * @uses Config::$CONTROLLERS_PATH
	 * @uses Config::$MODELS_PATH
	 * @uses Config::$LAYOUTS_PATH
	 * @uses Config::$VIEWS_PATH
	 * @uses Config::$COOKIE_PATH
	 * @uses Config::$PUBLIC_URL
	 * @uses Config::$instance
	 * @uses Routes
	 */
	static final function get_instance() {
		if ( self::$instance == null ) {
			self::$instance = new DefaultConfig();
		}
		return self::$instance;
	}

	/**
	 * Magic method. Returns a value to some private value
	 *
	 * @throws UndefinedVariable, if $key isnt found
	 * @param string $key
	 * @return mixed
	 */
	function __get( $key ) {
		if (!property_exists($this, $key)) {
			throw new UndefinedVariable(__CLASS__, $key);
		}
		return $this->$key;
	}
}

/**
 * For easier access to Config
 *
 * @return Config
 */
function Config() {
	return DefaultConfig::get_instance();
}


?>