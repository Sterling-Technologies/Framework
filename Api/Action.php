<?php //-->
/*
 * This file is part of the Openovate Labs Inc. framework library
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Api;

/**
 * The base class for any class that defines a view.
 * A view controls how templates are loaded as well as 
 * being the final point where data manipulation can occur.
 *
 * @package Framework
 */
abstract class Action extends Base 
{
    protected $body = array();
    
    /**
     * Simply calls render
     *
     * @return string
     */
    public function __toString() 
    {
        try {
            return $this->render();
        } catch(\Exception $e) {
			$this('core')->exception()->handler($e);
            $this->body['message'] = $e->getMessage();
        }
		
        try {
            return $this->fail();
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }
    
    /**
     * Sets a fail format
     *
     * @param mixed
     * @return string
     */
    protected function fail() 
    {
        $json = array('error' => true);
        
        if(isset($this->body['message']) && !empty($this->body['message'])) {
            $json['message'] = $this->body['message'];
        }
        
        if(isset($this->body['validation']) && !empty($this->body['validation'])) {
            $json['validation'] = $this->body['validation'];
        }
        
        header('Content-Type: text/json');
        return json_encode($json, JSON_PRETTY_PRINT);
    }
    
    /**
     * Transform block to string
     *
     * @return string
     */
    abstract public function render();
    
    /**
     * Sets a success format
     *
     * @param mixed
     * @return string
     */
    protected function success() 
    {
        $json = array('error' => false);
        
        if(!empty($this->body)) {
            $json['results'] = $this->body;
        }
        
        header('Content-Type: text/json');
        return json_encode($json, JSON_PRETTY_PRINT);
    }
}