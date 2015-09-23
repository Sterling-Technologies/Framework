<?php //-->
/*
 * This file is part of the Openovate Labs Inc. framework library
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Api;

use Handlebars\Handlebars;
use Handlebars\Loader\FilesystemLoader as HandlebarsLoader;
use Handlebars\SafeString;

/**
 * The base class for any class that defines a view.
 * A view controls how templates are loaded as well as 
 * being the final point where data manipulation can occur.
 *
 * @vendor Openovate
 * @package Framework
 */
abstract class Page extends Action 
{    
    const TEMPLATE_EXTENSION = 'html';
    
	protected $engine = null;
	
    protected $id = null;
    protected $title = null;
    protected $template = null;
    
    protected $meta    = array();
    protected $links = array();
    protected $styles = array();
    protected $scripts = array();
    protected $messages = array();
    
    protected $head = array();
    protected $foot = array();
    
	public function __construct() {
		//get the template path
		$path = control()->path('template');
		
		//make a new loader
		$loader = new HandlebarsLoader($path, array('extension' => self::TEMPLATE_EXTENSION));
		
		//create engine
		$this->engine = new Handlebars(array(
			'loader' => $loader,
			'partials_loader' => $loader));
		
		//add helpers
		$helpers = $this->getHelpers();
		
		foreach($helpers as $name => $callback) {
			$this->engine->registerHelper($name, $callback);
		}
	}
	
    /**
     * Transform block to string
     *
     * @return string
     */
    protected function fail() 
    {
		//we do it this way cuz of the parent definition
		$args = func_get_args();
		
		if(isset($args[0])) {
			$_SESSION['flash']['message'] = $args[0];
			$_SESSION['flash']['type'] = 'error';
		}
		
		if(isset($args[1])) {
			$this->body['errors'] = $args[1];
		}
		
		if(isset($args[2])) {
			$this->body['item'] = $args[2];
		}
		
        return $this->build('/error');
    }
    
    /**
     * Returns Helper functions
     *
     * @return array
     */
    protected function getHelpers() 
    {
        $control = control();
        $helpers = array();
        
        //i18n
        $helpers['_'] = function($key) use ($control) 
		{
            $args = func_get_args();
            $key = array_shift($args);
			$options = array_pop($args);
            
            return $control->translate((string) $key, $args);
        };
        
        //registry
        $helpers['registry'] = function() use ($control) 
		{
            $args = func_get_args();
			$options = array_pop($args);
            $data = $control->registry()->callArray('get', $args);
            
            if(is_object($data) && $data instanceof RegistryBase) {
                $data = $data->get(false);
            }
			
			if(is_object($data) || is_array($data)) {
				return $options['fn']((array) $data);
			}
            
            return $data;
        };
        
		//create session helpers
		$helpers['session'] = function($key, $options) 
		{
			if(!isset($_SESSION[$key])) {
				return $options['inverse']();
			}
			
			if(is_object($_SESSION[$key]) || is_array($_SESSION[$key])) {
				return $options['fn']((array) $_SESSION[$key]);
			}
            
            return $_SESSION[$key];
		};
		
		//create query helpers
		$helpers['server'] = function($key, $options) 
		{
			if(!isset($_SERVER[$key])) {
				return $options['inverse']();
			}
			
			if(is_object($_SERVER[$key]) || is_array($_SERVER[$key])) {
				return $options['fn']((array) $_SERVER[$key]);
			}
            
            return $_SERVER[$key];
		};
		
		//create query helpers
		$helpers['query'] = function($key, $options) 
		{
			if(!isset($_GET[$key])) {
				return $options['inverse']();
			}
			
			if(is_object($_GET[$key]) || is_array($_GET[$key])) {
				return $options['fn']((array) $_GET[$key]);
			}
            
            return $_GET[$key];
		};
		
		//create a better if helper
		$helpers['when'] = function($value1, $operator, $value2, $options) 
		{
			$valid = false;
		
			switch (true) {
				case $operator == 'eq' 	&& $value1 == $value2:
				case $operator == '==' 	&& $value1 == $value2:
				case $operator == 'req' && $value1 === $value2:
				case $operator == '===' && $value1 === $value2:
				case $operator == 'neq' && $value1 != $value2:
				case $operator == '!=' 	&& $value1 != $value2:
				case $operator == 'rneq' && $value1 !== $value2:
				case $operator == '!==' && $value1 !== $value2:
				case $operator == 'lt' 	&& $value1 < $value2:
				case $operator == '<' 	&& $value1 < $value2:
				case $operator == 'lte' && $value1 <= $value2:
				case $operator == '<=' 	&& $value1 <= $value2:
				case $operator == 'gt' 	&& $value1 > $value2:
				case $operator == '>' 	&& $value1 > $value2:
				case $operator == 'gte' && $value1 >= $value2:
				case $operator == '>=' 	&& $value1 >= $value2:
				case $operator == 'and' && $value1 && $value2:
				case $operator == '&&' 	&& ($value1 && $value2):
				case $operator == 'or' 	&& $value1 || $value2:
				case $operator == '||' 	&& ($value1 || $value2):
		
				case $operator == 'startsWith'
				&& strpos($value1, value2) === 0:
		
				case operator == 'endsWith'
				&& strpos($value1, $value2) === (strlen($value1) - strlen($value2)):
					$valid = true;
					break;
			}
		
			if($valid) {
				return $options['fn']();
			}
		
			return $options['inverse']();
		};
		
		//create a better loop helper
		$helpers['loop'] = function($object, $options) 
		{
			$i = 0;
			$buffer = array();
			$total = count($object);
			
			foreach($object as $key => $value) {
				$buffer[] = $options['fn'](array(
					'key'	=> $key,
					'value'	=> $value,
					'last'	=> ++$i === $total
				));
			}
			
			return implode('', $buffer);
		};
		
        //array key
        $helpers['in'] = function(array $array, $key, $options) 
		{
            if(!isset($array[$key])) {
                return $options['fn']();
            }

			return $options['inverse']();
        };
		
		//create time helper, used in /product-create template
		$helpers['time'] = function($offset, $options) 
		{
			$date = '';
            $offset = preg_replace('/\s/is', $offset);
			
			try {
				eval('$offset = ' . $offset);
				$date = date('Y-m-d', time() + $offset);
			} catch(Exception $e) {}
			
			return $date;
        };
		
		$helpers['date'] = function($time, $format, $options) 
		{
			return date($format, strtotime($time));
		};
        
        return $helpers;
    }
    
    /**
     * returns file ath used for templating
     *
     * @return array
     */
    protected function getTemplate() 
    {
        if(!$this->template) {
            $this->template = control('string')
				->set(get_class($this))
                ->str_replace('\\', DIRECTORY_SEPARATOR)
                ->strtolower()
                ->str_replace('api/action', '')
                ->get();
        }
        
        return $this->template;
    }
    
    /**
     * Returns the template loaded with specified data
     *
     * @param string
     * @param string|null
     * @param array
     * @return string
     */
    private function parse($file, $data = array(), $trigger = null) 
    {
        if(is_null($data)) {
            $data = array();
        } else if(is_string($data)) {
            $trigger = $data;
            $data = array();
        }
		
        if($trigger) {    
            control()->trigger('template-'.$trigger, $file, $data);
        }
        
		return $this->engine->render($file, $data);
    }
    
    /**
     * Transform block to string
     *
     * @return string
     */
    protected function build($template = null) 
    {
		//if no template
		if(!is_string($template)) {
			//get the default template
			$template = $this->getTemplate();
		}
        
        if(isset($_SESSION['flash']['message'])) {
            $this->body['flash']['message'] = $_SESSION['flash']['message'];
            $this->body['flash']['type'] = $_SESSION['flash']['type'];
            unset($_SESSION['flash']);
        }
        
		$body = $this->parse($template, $this->body);
		
        $page = array(
            'meta' => $this->meta,
            'links' => $this->links,
            'styles' => $this->styles,
            'scripts' => $this->scripts,
            'title' => $this->title,
            'class' => $this->id,
            'head' => $this->head,
            'body' => $body,
            'foot' => $this->foot);
            
        return $this->parse('_page', $page);
    }
    
    /**
     * Transform block to string
     *
     * @return string
     */
    protected function success() 
    {
		//we do it this way cuz of the parent definition
		$args = func_get_args();
		
		if(isset($args[0])) {
			$_SESSION['flash']['message'] = $args[0];
			$_SESSION['flash']['type'] = 'success';
		}
		
		if(isset($args[1])) {
			//this will exit anyways
			control()->redirect($args[1]);
		}
		
       	return $this->build($this->getTemplate());
    }
}