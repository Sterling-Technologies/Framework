<?php //-->
/*
 * This file is part of the Persistent package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace OL\App\Front;

use Eve\Framework\Exception;
use Eve\Framework\Argument;
use Eve\Framework\Base;

use OL\App\Dialog\Action\Invalid;

/**
 * Validates dialog requests 
 *
 * @vendor Openovate
 * @package Framework
 * @author Christian Blanquera cblanquera@openovate.com
 */
class Route extends Base 
{
    const INSTANCE = 1;
    
    public $routes = array(
        '*' => array(
            'method' => 'ALL',
            'class' => '\\OL\\App\\Front\\Action\\Index'
        )
    );
    
    /**
     * Main route method
     *
     * @return function
     */
    public function import() 
    {
        //remember this scope
        $self = $this;
        
        //loop through routes
        foreach($this->routes as $route => $meta) {
            //form the callback
            $callback = function($request, $response) use ($self, $route, $meta) {
                
                $path = $request->get('path', 'string');
                
                $variables = $self->getVariables($route, $path);
                
                //set the route
                $request->set('route', $route);
                
                //set variables
                $request->set('variables', $variables);
                
                //set the action
                $response->set('action', $meta['class']);
                
                //set paths
                eve()->registry()->set('path', 'action', __DIR__.'/Action');
                eve()->registry()->set('path', 'template', __DIR__.'/template');
                
                $action = $meta['class'];
                
                //it's a class
                $instance = new $action();
                
                //call it
                $results = $instance
                    ->setRequest($request)
                    ->setResponse($response)
                    ->render();
                
                //if there are results
                //and no body was set
                if($results 
                && is_scalar($results)
                && !$response->isKey('body')) {
                    $response->set('body', (string) $results);
                }
                
                //prevent something else from taking over
                if($response->isKey('body')) {
                    return false;
                }
            };
            
            //add route
            eve()->route($meta['method'], $route, $callback);
        }
        
        //You can add validators here
        return function($request, $response) use ($self) {};    
    }
    
    /**
     * Returns a dynamic list of variables
     * based on the given pattern and path
     *
     * @return array
     */
    public function getVariables($route, $path) 
    {
        $variables = array();
        
        $regex = str_replace('**', '!!', $route);
        $regex = str_replace('*', '([^/]*)', $regex);
        $regex = str_replace('!!', '(.*)', $regex);
        
        $regex = '#^'.$regex.'(.*)#';
        
        if(!preg_match($regex, $path, $matches)) {
            return $variables;
        }
        
        if(!is_array($matches)) {
            return $variables;
        }
        
        array_shift($matches);
        
        foreach($matches as $path) {
            $variables = array_merge($variables, explode('/', $path));
        }
        
        foreach($variables as $i => $variable) {
            if(!$variable) {
                unset($variables[$i]);
            }
        }
        
        return array_values($variables);
    }
}