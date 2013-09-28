<?php //-->
/*
 * This file is part of the Openovate Labs Inc. framework library
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
 
require_once __DIR__.'/../vendor/autoload.php';

/**
 * The starting point of every application call. If you are only
 * using the framework you can rename this function to whatever you
 * like.
 *
 */
function control() {
	$class = Control::i();
	if(func_num_args() == 0) {
		return $class;
	}

	$args = func_get_args();
	
	return $class->__invoke($args);
}

/**
 * Defines the starting point of every site call.
 * Starts laying out how classes and methods are handled.
 *
 * @vendor Openovate
 * @package Framework
 */
class Control extends Eden\Core\Controller 
{
	protected $application = 'application';
	protected $defaultDatabase = null;
	protected $dataRegistry = null;
	protected $defaultLanguage = null;
	
	protected static $uid = 1;
	
	/**
	 * Construct - start application loader
	 *
	 * @return void
	 */
	public function __construct() 
	{
		parent::__construct();
		
		//turn on autoloader
		Eden\Core\Loader::i()
			->addRoot(__DIR__, '')
			->addRoot(realpath(__DIR__.'/../package'), '')
			->register();
	}
	
	/**
	 * Calls response __toString
	 *
	 * @return string
	 */
	public function __toString() 
	{
		try {
			$response = (string) $this->registry()->get('response');
		} catch(Exception $e) {
			$this('core')->exception()->handler($e);
			$response = '';
		}
		
		return $response;
	}
	
	/**
	 * Returns the given application block
	 *
	 * @return Eden\Block\Factory
	 */
	public function block($name) 
	{
		$args 	= func_get_args();
		$name 	= array_shift($args);
		$class 	= ucwords($this->application).'\\Block\\'.ucwords($name);

		if(class_exists($class)) {
			return $this('core')
			->route()
			->callArray($class, $args);
		}

		return null;
	}
	
	/**
	 * Returns or saves the config 
	 * data given the key
	 *
	 * @param string
	 * @return array
	 */
	public function config($key, array $data = null) 
	{
		$this('core')->argument()->test(1, 'string');
		
		$path = $this->path('config');
		
		$file = $this('system')->file($path.'/'.$key.'.php');
		
		if(is_array($data)) {
			$file->setData($data);
			return $this;
		}
		
		if(!file_exists($file)) {
			return array();
		}
		
		return $file->getData();
	}
	
	/**
	 * Returns the default database instance
	 *
	 * @param string|null
	 * @return Eden\Sql\Factory
	 */
	public function database($key = null) 
	{
		$this('core')->argument()->test(1, 'string', 'null');
		
		if(is_null($key)) {
			//return the default database
			return $this->defaultDatabase;
		}
		
		return $this->registry()->get('database', $key);
	}
	
	/**
	 * Returns the current Language
	 *
	 * @return Eden\Language\Base
	 */
	public function language() 
	{
		if(is_null($this->defaultLanguage)) {
			$settings = $this->config($this->application.'/settings');
			
			$config = $this->path('config');
			$path = $config.'/i18n/'.$settings['i18n'].'.php';
			
			$translations = array();
			
			if(file_exists($path)) {
				$translations = $this->config('i18n/'.$settings['i18n']);
			}
			
			$this->defaultLanguage = $this('language', $translations);
		}
		
		return $this->defaultLanguage;
	}
	
	/**
	 * Returns the absolute path 
	 * given the key
	 *
	 * @param string
	 * @return string
	 */
	public function path($key) 
	{
		$this('core')->argument()->test(1, 'string');
		
		return $this->registry()->get('path', $key);
	}
	
	/**
	 * Force renders
	 *
	 * @return Control
	 */
	public function render() 
	{
		echo $this;
		return $this;	
	}
	
	/**
	 * Browser Redirect
	 *
	 * @param path
	 * @return void
	 */
	public function redirect($path) 
	{
		header('Location: '.$path);
		exit;
	}
	
	/**
	 * Returns the current Registry
	 *
	 * @return Eden\Registry\Base
	 */
	public function registry() 
	{
		if(!$this->dataRegistry) {
			$this->dataRegistry = $this('registry');
		}
		
		return $this->dataRegistry;
	}
	
	/**
	 * Sets application name
	 *
	 * @param string
	 * @return Control
	 */
	public function setApplication($name) 
	{
		$this->application = $name;
		$this->registry()->set('application', $name);
		return $this;
	}
	
	/**
	 * Sets up the default database connection
	 *
	 * @return Control
	 */
	public function setDatabases() 
	{
		$databases = $this->config($this->application.'/databases');
		
		foreach($databases as $key => $info) {	
			//connect to the data as described in the config
			switch($info['type']) {
				case 'postgre':
					$database = $this(
						'postgre',
						$info['host'], 
						$info['name'], 
						$info['user'], 
						$info['pass']);
					break;
				case 'mysql':
					$database = $this(
						'mysql',
						$info['host'], 
						$info['name'], 
						$info['user'], 
						$info['pass']);
					break;
				case 'sqlite':
					$database = $this('sqlite', $info['file']);
					break;
			}
			
			$this->registry()->set('database', $key, $database);
			
			if($info['default']) {
				$this->defaultDatabase = $database;
			}
		}
		
		return $this;
	}
	
	/**
	 * Lets the framework handle exceptions.
	 * This is useful in the case that you 
	 * use this framework on a server with
	 * no xdebug installed.
	 *
	 * @return Control
	 */
	public function setDebug() 
	{
		$this('core')
			->argument()
			->test(1, 'int', 'null')
			->test(2, 'bool', 'null');
		
		//get settings from config
		$settings = $this->config($this->application.'/settings');
		
		//if debug mode is on
		if($settings['eden_debug']) {
			$template = $this->path('template');
			
			$handler = function(
				$error, 
				$event, 
				$type, 
				$level, 
				$class, 
				$file, 
				$line, 
				$message, 
				$trace, 
				$offset
			) use ($template) { 
				$history = array();
				for(; isset($trace[$offset]); $offset++) {
					$row = $trace[$offset];
					 
					//lets formulate the method
					$method = $row['function'].'()';
					if(isset($row['class'])) {
						$method = $row['class'].'->'.$method;
					}
					 
					$rowLine = isset($row['line']) ? $row['line'] : 'N/A';
					$rowFile = isset($row['file']) ? $row['file'] : 'Virtual Call';
					 
					//add to history
					$history[] = array($method, $rowFile, $rowLine);
				}
				
				echo Eden\Template\Base::i()
					->set('history', $history)
					->set('type', $type)
					->set('level', $level)
					->set('class', $class)
					->set('file', $file)
					->set('line', $line)
					->set('message', $message)
					->parsePhp($template.'/error.php');
			};
			
			//turn on error handling
			$this('core')
				->error()
				->register()
				->listen('error', $handler)
				->when(!is_null($settings['debug_mode']), function($instance) use ($settings) {
					$instance->setReporting($settings['debug_mode']);
				});
			
			//turn on exception handling
			$this('core')
				->exception()
				->register()
				->listen('exception', $handler);
			
			return $this;
		}
		
		//at this point debug mode is off
		//release and unlisten to an error event
		$this('core')
			->error()
			->setReporting(0)
			->release()
			->unlisten('error');
		
		//release and unlisten to an exception event
		$this('core')
			->exception()
			->release()
			->unlisten('error');
		
		return $this;
	}
	
	/**
	 * Sets the application absolute paths
	 * for later referencing
	 * 
	 * @return Control
	 */
	public function setPaths() 
	{
		$repo = __DIR__;
		$root = realpath($repo.'/..');
		
		$this->registry()
		//root paths
		->set('path', 'root', $root)
		->set('path', 'repo', $repo)
		
		//paths that are dynamic and should not be committed
		->set('path', 'package', $root.'/package')
		->set('path', 'config', $root.'/config')
		->set('path', 'upload', $root.'/upload')
		
		//repo folders
		->set('path', 'page', $repo.'/'.ucwords($this->application).'/page')
		->set('path', 'template', $repo.'/'.ucwords($this->application).'/template')
		->set('path', 'block', $repo.'/'.ucwords($this->application).'/block')
		->set('path', 'public', $repo.'/'.ucwords($this->application).'/public');
		
		
		//get settings from config
		$settings = $this->config($this->application.'/settings');
		
		//web paths
		$this->registry()
			->set('path', 'url', $settings['url_root'])
			->set('path', 'cdn', $settings['cdn_root']);
		
		return $this;
	}
	
	/**
	 * Sets request
	 *
	 * @param EdenRegistry|null the request object
	 * @return Control
	 */
	public function setRequest() 
	{
		$application 	= $this->registry()->get('application');
		$prefix 		= ucwords($application).'\\Page';
		$path 			= $_SERVER['REQUEST_URI'];

		//remove ? url queries
		if(strpos($path, '?') !== false) {
			list($path, $tmp) = explode('?', $path, 2);
		}

		$array 		= explode('/',  $path);
		$variables 	= array();
		$page 		= null;
		$buffer 	= $array;

		while(count($buffer) > 1) {
			$parts = ucwords(implode(' ', $buffer));
			$class = $prefix.str_replace(' ', '\\', $parts);

			if(class_exists($class)) {
				$page = $class;
				break;
			}

			$variable = array_pop($buffer);
			array_unshift($variables, $variable);
		}

		$path = array(
		'string' 	=> $path,
		'array' 	=> $array,
		'variables'	=> $variables);

		//set the request
		$this->registry()
		->set('server'	, $_SERVER)
		->set('cookie'	, $_COOKIE)
		->set('get'		, $_GET)
		->set('post'	, $_POST)
		->set('files'	, $_FILES)
		->set('request'	, $path)
		->set('page'	, $page);

		return $this;
	}
	
	/**
	 * Sets response
	 *
	 * @param string|null the request object
	 * @return Control
	 */
	public function setResponse($default) 
	{
		$page = $this->registry()->get('page');
		$default = ucwords($default);
		
		if(!$page || !class_exists($page)) {
			if(!class_exists($default)) {
				$application 	= $this->registry()->get('application');
				$prefix 		= ucwords($application).'\\Page';
				$default 		= $prefix.'\\'.$default;
			}
			
			$page = $default;
		}
		
		//set the response data
		$response = $this->$page();
		
		$this->registry()->set('response', $response);
		
		return $this;
	}

	/**
	 * Sets the PHP timezone
	 *
	 * @param *string
	 * @return Eden\Core\Controller
	 */
	public function setTimezone($zone = 'GMT')
	{
		$settings = $this->config($this->application.'/settings');

		date_default_timezone_set($settings['server_timezone']);

		return $this;
	}
	
	/**
	 * Starts up packages
	 *
	 * @return Control
	 */
	public function startPackages() 
	{
		foreach($this->config('packages') as $slug => $module) {
			$bootstrap = $this->registry()
				->get('path', 'package')
				.'/'.$slug.'/bootstrap.php';
			
			if(!file_exists($bootstrap)) {
				continue;
			}
			
			include $bootstrap;
		}
		
		return $this;
	}
	
	/**
	 * Returns the template loaded with specified data
	 *
	 * @param array
	 * @return Eden\Template\Factory
	 */
	public function template($file, array $data = array()) 
	{
		$this('core')->argument()->test(1, 'string');
		
		return $this('template')->set($data)->parsePhp($file);
	}
	
	/**
	 * Save translation
	 *
	 * @return Control
	 */
	public function saveTranslation() 
	{
		$settings = $this->config($this->application.'/settings');
		$config = $this->path('config');
		$path = $config.'/i18n/'.$settings['i18n'].'.php';
		
		$this->language()->save($path);
		
		return $this;
	}
	
	/**
	 * Translate string
	 *
	 * @param string
	 * @return string
	 */
	public function translate($string) 
	{
		$this('core')->argument()->test(1, 'string');
		
		return $this->language()->get($string);
	}
	
	/**
	 * Generates an all pupose uid
	 *
	 * @return string
	 */
	public function uid() 
	{
		return 'control'.time().'-'.self::$uid++;
	}
}
