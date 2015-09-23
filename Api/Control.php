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
	require_once __DIR__ . '/vendor/autoload.php';
	
    /**
     * The starting point of every application call. If you are only
     * using the framework you can rename this function to whatever you
     * like.
     */
    function control() 
	{
		$class = Api\Control::i();
        
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
    class Control extends \Eden\Server\Index
    {
        const INSTANCE = 1;
        
        public $defaultDatabase = null;
        public $defaultRegistry = null;
        public $defaultLanguage = null;
        
		/**
         * Runs the default bootstrap from start to finish
		 * If you wish to add process between these steps
		 * you should copy the method details and paste to 
		 * index.php
         *
         * @return this
         */
		public function defaultBootstrap() 
		{
			return $this
				->defaultPaths()
				->defaultDebugging()
				->defaultErrorHandler()
				//->defaultDatabases()
				->defaultEvents()
				->trigger('config')
				->defaultTimezone('Asia/Manila')
				->trigger('init')
				->defaultSession()
				->trigger('session')
				->defaultRouting()
				->trigger('request')
				->defaultResponse()
				->trigger('response')
				->render()
				->trigger('render')
				->trigger('shutdown');
		}
		
        /**
         * Sets up the default database connection
         *
		 * @param array inject a database config
         * @return this
         */
        public function defaultDatabases(array $databases = null) 
        {
			if(!$databases) {
            	$databases = $this->settings('databases');
			}
			
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
         * @return this
         */
        public function defaultDebugging() 
        {
			//get settings from config
			$config = $this->settings('config');
			
			//save it for later
			$this->registry()->set('config', $config);
			
			//if debug mode is on
			if(!$config['debug_mode']) {
				//stop argument testing
				Argument::i()->stop();
			}
			
			//turn on error handling
			$error = $this('handler')
				->error()
				->register()
				->setReporting($config['debug_mode']);
			
			//turn on exception handling
			$exception = $this('handler')
				->exception()
				->register();
			
            return $this;
        }
		
		/**
         * Sets Default Error Handlers
         *
         * @return this
         */
		public function defaultErrorHandler() 
		{
			//this happens on an error
			$this->error(function($request, $response) {
				$args = func_get_args();
				$request = array_shift($args);
				$response = array_shift($args);
				
				$mode = $this->registry()->get('config', 'debug_mode');
				
				$type = 'text/plain';
				if(!$response->isKey('headers', 'Content-Type')) {
					$response->set('headers', 'Content-Type', $type);
				} else {
					$type = $response->get('headers', 'Content-Type');
				}
				
				$handler = new Error();
				
				switch(true) {
					case strpos($type, 'html') !== false && $mode:
						$body = $handler->callArray('htmlDetails', $args);
						break;
					case strpos($type, 'html') !== false:
						$body = $handler->callArray('htmlGeneric', $args);
						break;
					case strpos($type, 'json') !== false && $mode:
						$body = $handler->callArray('jsonDetails', $args);
						break;
					case strpos($type, 'json') !== false:
						$body = $handler->callArray('jsonGeneric', $args);
						break;
					case strpos($type, 'plain') !== false && $mode:
						$body = $handler->callArray('plainDetails', $args);
						break;
					case strpos($type, 'plain') !== false:
					default:
						$body = $handler->callArray('plainGeneric', $args);
						break;
				}
				
				$response->set('body', $body);
			});
			
			return $this;
		}
    
        /**
         * Starts a event
         *
         * @return this
         */
        public function defaultEvents() 
        {
            //traverse through the event folder
			$path = $this->path('event');
			$files = $this('folder')->set($path)->getFiles();
            
			//from
			//   /root/Api/Event/request-filter.php
			//  control()->on('request', function() {
			//		// IF registry()->get('request', 'path') startswith /rest/
			//		// THEN check against database
			//		// if invalid, output invalid and exit.
			//  });
            return $this;
        }
		
        /**
         * Sets the application absolute paths
         * for later referencing
         * 
         * @return this
         */
        public function defaultPaths() 
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
         * Sets response
         *
         * @param string|null the request object
         * @return this
         */
        public function defaultResponse() 
        {
			$this->all('*', function($request, $response) {
				//if there is already a body
				if($response->isKey('body')
				|| !$response->isKey('action')) {
					//do nothing
					return;
				}
				
				$class = $response->get('action');
				$action = new $class();
				$body = $action->render();
				$response->set('body', $body);
			});
			
            return $this;
        }
		
		/**
         * Sets Dynamic routes base on the request
         *
         * @return this
         */
		public function defaultRouting()
		{
			//just call the parent
			$this->all('*', function($request, $response) {
				//if there is already a body
				if($response->isKey('body')) {
					//do nothing
					return;
				}
				
				$prefix = '\\Api\\Action';
	
				$path = $request['path']['string'];
				$array = $request['path']['array'];
				
				$variables = array();
				$action = null;
				$buffer = $array;
				
				while(count($buffer) > 1) {
					$parts = ucwords(implode(' ', $buffer));
					$class = $prefix.str_replace(' ', '\\', $parts);
					
					if(class_exists($class)) {
						$action = $class;
						break;
					}
			
					$variable = array_pop($buffer);
					array_unshift($variables, $variable);
				}
				
				
				
				if(!$action || !class_exists($action)) {
					$default = $this->registry()->get('config', 'default_page');
					$default = ucwords($default);
					$default = '\\Api\\Action\\'.$default;
					
					if(class_exists($default)) {
						$action = $default;
					}
				}
				
				//set the reuqest
				$request->set('path', 'variables', $variables);
				
				//if we have an action
				if($action) {
					//set the action
					$response->set('action', $action);
				}
			});
			
			return $this;
		}
    
        /**
         * Starts a session
         *
         * @return this
         */
        public function defaultSession() 
        {
            session_start();
            
            return $this;
        }
        
        /**
         * Sets the PHP timezone
         *
         * @param *string
         * @return this
         */
        public function defaultTimezone($zone = 'GMT') 
        {
            $settings = $this->settings('config');
    
            date_default_timezone_set($settings['server_timezone']);
    
            return $this;
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
         * Factory for helper
         *
         * @return Api\Helper
         */
        public function help() 
        {
            return Helper::i();
        }
        
        /**
         * Returns the current Language
         *
         * @return Eden\Language\Index
         */
        public function language() 
        {
            if(is_null($this->defaultLanguage)) {
                $config = $this->settings('config');
                
                $settings = $this->path('settings');
                $path = $settings.'/i18n/'.$config['i18n'].'.php';
                
                $translations = array();
                
                if(file_exists($path)) {
                    $translations = $this->settings('i18n/'.$config['i18n']);
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
            
            $file = $this('file')->set($path.'/'.$key.'.php');
            
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
         * Gives the ability to add Queues
         *
         * @param *string
         * @param *array
         * @return string
         */        
        public function queue($task, array $data)
        {   
            $config = $this->path('settings');
            $config = $config['queue'];

            return Queue::i(
                $config['host'],
                $config['port'], 
                $config['username'], 
                $config['password'],
                $task,
                $data);
        }

        /**
         * Provides instance of the Job
         *
         * @param *string
         * @param *array
         * @return string
         */
        public function job($task, array $data)
        {   
            $class = 'Api\\Job\\' . str_replace('-', '\\', $task); 
            if (class_exists($class)) {
                return $this->class();
            }

            return null;
        }
    }
}