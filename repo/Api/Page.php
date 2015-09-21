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
    const TEMPLATE_EXTENSION = 'phtml';
    
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
    
    /**
     * Transform block to string
     *
     * @return string
     */
    protected function fail() 
    {
        return $this->build('/error');
    }
    
    /**
     * Returns Helper functions
     *
     * @return array
     */
    protected function getHelpers() 
    {
        $self = control();
        $helpers = array();
        
        //i18n
        $helpers['_'] = function($key) use ($self) {
            $args = func_get_args();
            $key = array_shift($args);
            
            echo $self->translate($key, $args);
        };
        
        //array key
        $helpers['a'] = function(array $array, $key) {
            if(!isset($array[$key])) {
                return;
            }

            echo $array[$key];
        };
        
        //block
        $helpers['b'] = function($type = null) use ($self) {
            if(!$type) {
                return $self('block');
            }
            
            return $self('block')->$type();
        };
        
        //controller
        $helpers['c'] = function() use ($self) {
            return $self;
        };
        
        //data
        $helpers['d'] = function($data, $default) use ($self) {
            if($data) {
                echo $data;
                return;
            }
            
            echo $default;
            return;
        };
        
        //echo
        $helpers['e'] = function($bool, $true = null, $false = null) use ($self) {
            if(is_null($true)) {
                echo $bool;
                return;
            }
            
            echo $bool ? $true : $false;
        };
        
        //registry
        $helpers['r'] = function() use ($self) {
            $args = func_get_args();
            $data = $self->registry()->callArray('get', $args);
            
            if(is_object($data) && $data instanceof RegistryBase) {
                $data = $data->get(false);
            }
            
            return $data;
        };
        
        //template
        $helpers['t'] = function($file, $data = array(), $trigger = null) use ($self) {
            echo $self->template($file, $data, $trigger);
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
            $this->template = control('string', get_class($this))
                ->replace('_', DIRECTORY_SEPARATOR)
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
        
        $object = new \StdClass();
        $object->file = $file;
        $object->data = $data;
        
        if($trigger) {    
            control()->trigger('template-'.$trigger, $object);
        }
        
        $object->data = array_merge(
            $this->getHelpers($data), 
            $object->data);
        
        return control('template')->set($object->data)->parsePhp($object->file);
    }
    
    /**
     * Transform block to string
     *
     * @return string
     */
    protected function build($template) 
    {
        $this->body['messages'] = array();
        if(isset($_SESSION['messages'])) {
            $this->body['messages'] = $_SESSION['messages'];
            $_SESSION['messages'] = array();
        }
        
        $path = control()->path('template');
        
        $head = $this->parse($path.'/_head.'.static::TEMPLATE_EXTENSION, $this->head);
        $body = $this->parse($path.$template.'.'.static::TEMPLATE_EXTENSION, $this->body);
        $foot = $this->parse($path.'/_foot.'.static::TEMPLATE_EXTENSION, $this->foot);
        
        $page = array(
            'meta' => $this->meta,
            'links' => $this->links,
            'styles' => $this->styles,
            'scripts' => $this->scripts,
            'title' => $this->title,
            'class' => $this->id,
            'head' => $head,
            'body' => $body,
            'foot' => $foot);
            
        return $this->parse($path.'/_page.'.static::TEMPLATE_EXTENSION, $page);
    }
    
    /**
     * Transform block to string
     *
     * @return string
     */
    protected function success() 
    {
       return $this->build($this->getTemplate());
    }
}