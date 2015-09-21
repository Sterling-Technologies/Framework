<?php //-->
/*
 * This file is part of the Openovate Labs Inc. framework library
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace
{
    /**
     * The starting point of every application call. If you are only
     * using the framework you can rename this function to whatever you
     * like.
     */
    function control() {
        $class = Api_Control::i();
        if(func_num_args() == 0) {
            return $class;
        }
    
        $args = func_get_args();
        
        return $class->__invoke($args);
    }
}

namespace Api 
{
    /**
     * Defines the starting point of every site call.
     * Starts laying out how classes and methods are handled.
     *
     * @package Api
     */
    class Control extends Base 
    {
        const INSTANCE = 1;
        
        public $defaultDatabase = null;
        public $defaultRegistry = null;
        public $defaultLanguage = null;
        
		protected static $uid = 1;
		
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
            
            return (string) $response;
        }
        
        /**
         * Returns the default database instance
         *
         * @param string|null
         * @return mixed
         */
        public function database($key = null) 
        {
            Argument::i()->test(1, 'string', 'null');
            
            if(is_null($key)) {
                //return the default database
                return $this->defaultDatabase;
            }
            
            return $this->registry()->get('database', $key);
        }
        
        /**
         * Returns the current Language
         *
         * @return Eden_Language_Index
         */
        public function language() 
        {
            if(is_null($this->_language)) {
                $settings = $this->settings('settings');
                
                $settings = $this->path('settings');
                $path = $settings.'/i18n/'.$settings['i18n'].'.php';
                
                $translations = array();
                
                if(file_exists($path)) {
                    $translations = $this->settings('i18n/'.$settings['i18n']);
                }
                
                $this->defaultLanguage = $this('language', $translations);
            }
            
            return $this->defaultLanguage;
        }
		
        /**
         * Loads a model (not a database model)
         *
         * @param string|null
         * @return mixed
         */
        public function model($key) 
        {
            Argument::i()->test(1, 'string');
            
			$class = 'Api\\Model\\' . $key . '\\Index';
			
			if(!class_exists($class)) {
				throw new Exception(sprintf('No Model: %s Found', $key));
			}
			
			return $this->$class();
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
            Argument::i()->test(1, 'string');
            
            return $this->registry()->get('path', $key);
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
         * @return Eden_Registry_Index
         */
        public function registry() 
        {
            if(!$this->defaultRegistry) {
                $this->defaultRegistry = $this('registry');
            }
            
            return $this->defaultRegistry;
        }
        
        /**
         * Force renders
         *
         * @return Control
         */
        public function render() 
        {
            echo $this;        
            return $this->trigger('render')->trigger('shutdown');    
        }
        
        /**
         * Sets up the default database connection
         *
         * @return Controller
         */
        public function setDatabases() 
        {
            $databases = $this->settings('databases');
            
            foreach($databases as $key => $info) {    
                //connect to the data as described in the settings
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
         * @return Controller
         */
        public function setDebug() 
        {
            //get settings from config
            $settings = $this->settings('config');
            $handler = new Api_Error();
            $callback = array($handler, 'outputDetails');
            
            //if debug mode is on
            if(!$settings['debug_mode']) {
                //stop argument testing
                Argument::i()->stop();
                
                $callback = array($handler, 'outputGeneric');
            }
            
            //turn on error handling
            $error = $this('handler')
                ->error()
                ->register()
                ->on('error', $callback)
                ->setReporting($settings['debug_mode']);
            
            //turn on exception handling
            $this('handler')
                ->exception()
                ->register()
                ->on('exception', $callback);
            
            return $this;
        }
        
        /**
         * Sets the application absolute paths
         * for later referencing
         * 
         * @return Controller
         */
        public function setPaths() 
        {
            $root = __DIR__;
            
            $this->registry()
                //root paths
                ->set('path', 'root', $root)
                
                //paths that are dynamic and should not be committed
                ->set('path', 'settings', $root.'/settings')
                ->set('path', 'upload', $root.'/upload')
                ->set('path', 'vendor', $root.'/vendor')
                
                //PHP folders
                ->set('path', 'action', $root.'/Action')
                ->set('path', 'event', $root.'/Event')
                ->set('path', 'job', $root.'/Job')
                ->set('path', 'model', $root.'/Model')
                
                //Other Folders
                ->set('path', 'template', $root.'/template')
                ->set('path', 'public', $root.'/public');
            
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
            $prefix = 'Api_Action';
            $path = $_SERVER['REQUEST_URI'];
    
            //remove ? url queries
            if(strpos($path, '?') !== false) {
                list($path, $tmp) = explode('?', $path, 2);
            }
    
            $array = explode('/',  $path);
            $variables = array();
            $action = null;
            $buffer = $array;
    
            while(count($buffer) > 1) {
                $parts = ucwords(implode(' ', $buffer));
                $class = $prefix.str_replace(' ', '_', $parts);
                
                if(class_exists($class)) {
                    $action = $class;
                    break;
                }
    
                $variable = array_pop($buffer);
                array_unshift($variables, $variable);
            }
    
            $path = array(
                'string' => $path,
                'array' => $array,
                'variables' => $variables);
    
            //set the request
            $this->registry()
                ->set('server', $_SERVER)
                ->set('cookie', $_COOKIE)
                ->set('get', $_GET)
                ->set('post', $_POST)
                ->set('files', $_FILES)
                ->set('request', $path)
                ->set('action', $action);
    
            return $this;
        }
        
        /**
         * Sets response
         *
         * @param string|null the request object
         * @return Control
         */
        public function setResponse() 
        {
            $action = $this->registry()->get('action');
            
            if(!$action || !class_exists($action)) {
                $settings = $this->settings('config');
                $default = ucwords($settings['default_page']);
                
                if(!class_exists($action)) {
                    $default = 'Api_Action_'.$default;
                }
                
                $page = $default;
            }
            
            //set the response data
            $response = new $page();
    
            $this->registry()->set('response', $response);
            
            return $this;
        }
        
        /**
         * Returns or saves the settings 
         * data given the key
         *
         * @param string
         * @return this|array
         */
        public function settings($key, array $data = null) 
        {
            Argument::i()->test(1, 'string');
            
            $path = $this->path('settings');
            
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
         * Sets the PHP timezone
         *
         * @param *string
         * @return Controller
         */
        public function setTimezone($zone = 'GMT') 
        {
            $settings = $this->settings('settings');
    
            date_default_timezone_set($settings['server_timezone']);
    
            return $this;
        }
    
        /**
         * Starts a session
         *
         * @return Control
         */
        public function startSession() 
        {
            session_start();
            
            return $this;
        }
        
        /**
         * Translate string
         *
         * @param *string
         * @return string
         */
        public function translate($string, $args = array()) 
        {
            Argument::i()->test(1, 'string');
            
            if(!is_array($args)) {
                $args = func_get_args();
                $string = array_shift($args);
            } 
            
            if(count($args)) {
                foreach($args as $i => $arg) {
                    $args[$i] = $this->language()->get($arg);
                }
                
                return vsprintf($this->language()->get($string), $args);
            }
            
            return $this->language()->get($string);
        }
	
		/**
		 * Generates an all pupose uid
		 *
		 * @return string
		 */
		public function uid() 
		{
			return md5('control'.time().'-'.self::$uid++);
		}
    }
}