<?php //-->
/*
 * This file is part of the Persistent package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace OL\App\Rest;

use Eve\Framework\Exception;
use Eve\Framework\Argument;
use Eve\Framework\Base;

/**
 * Validates rest requests 
 *
 * @vendor Openovate
 * @package Framework
 * @author Christian Blanquera cblanquera@openovate.com
 */
class Route extends Base 
{
    const FAIL_401 = 'Invalid Request';
    
    public $routes = array(
        '/rest/profile/search' => array(
            'method' => 'GET',
            'role' => 'public_profile',
            'class' => '\\OL\\App\\Rest\\Action\\Profile\\Search'
        ),
        '/rest/profile/detail/*' => array(
            'method' => 'GET',
            'role' => 'public_profile',
            'class' => '\\OL\\App\\Rest\\Action\\Profile\\Detail'
        ),
        '/rest/access' => array(
            'method' => 'POST',
            'role' => 'public_sso',
            'class' => '\\OL\\App\\Rest\\Action\\Access'
        ),
        '/rest/user/profile/detail' => array(
            'method' => 'GET',
            'role' => 'user_profile',
            'class' => '\\OL\\App\\Rest\\Action\\Profile\\Detail'
        ),
        '/rest/user/profile/update' => array(
            'method' => 'PUT',
            'role' => 'user_profile',
            'class' => '\\OL\\App\\Rest\\Action\\Profile\\Update'
        ),
        '/rest/profile/detail' => array(
            'method' => 'GET',
            'role' => 'personal_profile',
            'class' => '\\OL\\App\\Rest\\Action\\Profile\\Detail'
        ),
        '/rest/profile/update' => array(
            'method' => 'PUT',
            'role' => 'personal_profile',
            'class' => '\\OL\\App\\Rest\\Action\\Profile\\Update'
        )
    );
    
    /**
     * This is what happens if it's invalid
     *
     * @param Eden\Registry\Index
     * @param Eden\Registry\Index
     * @return false
     */
    public function fail($request, $response) 
    {
        $response->set('body', json_encode(array( 
            'error' => true, 
            'message' => self::FAIL_401 ), 
        JSON_PRETTY_PRINT));
        
        return false;
    }
    
    /**
     * Main plugin method
     *
     * @return function
     */
    public function import() 
    {
        //remember this scope
        $self = $this;
        
        //loop through routes
        foreach($self->routes as $route => $meta) {
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
                
                //start calling it
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
        return function($request, $response) use ($self) {
            //set the header
            $response->set('headers', 'Content-Type', 'text/json');
        
            //get the method
            $method = $request->get('method');
        
            //get the path
            $path = $request->get('path', 'string');
            
            //if this is not a dialog call
            if($path !== '/rest' && strpos($path, '/rest/') !== 0) {
                //business as usual
                return;
            }
            
            //see if there is a route
            list($route, $meta, $variables) = $self->getRoute($request);
            
            //if no route was found
            if(!$route) {
                //don't allow
                return $self->fail($request, $response);
            }
            
            //yay we found a route...
            
            //now we are going to validate this OAUTH session
            
            //determine the token
            $token = null;
            
            if($request->isKey('get', 'access_token')) {
                $token = $request->get('get', 'access_token');
            }
            
            if($request->isKey('post', 'access_token')) {
                $token = $request->get('post', 'access_token');
            }
            
            if($request->isKey('get', 'client_id')) {
                $token = $request->get('get', 'client_id');
            }
            
            if($request->isKey('post', 'client_id')) {
                $token = $request->get('post', 'client_id');
            }
            
            //determine the secret
            $secret = null;
            
            if($request->isKey('get', 'access_secret')) {
                $secret = $request->get('get', 'access_secret');
            }
            
            if($request->isKey('post', 'access_secret')) {
                $secret = $request->get('post', 'access_secret');
            }
            
            if($request->isKey('get', 'client_secret')) {
                $secret = $request->get('get', 'client_secret');
            }
            
            if($request->isKey('post', 'client_secret')) {
                $secret = $request->get('post', 'client_secret');
            }
                
            //must have access token
            //if not get must have access secret
            //a flattened if looks very confusing
            //lets test this case more rudimentary
            
            //if there is no token in general
            if(!$token) {
                //all rest must include an access token
                return $self->fail($request, $response);
            }
            
            //if it's not a GET
            if(strtoupper($method) !== 'GET' && !$secret) {
                //don't allow
                return $self->fail($request, $response);
            } 
            
            //if no meta role
            if(!isset($meta['role'])) {
                //set it
                $meta['role'] = null;
            }
            
            //if user
            if(strpos($meta['role'], 'user_') === 0) {
                return $self->validateUser(
                    $request, 
                    $response, 
                    $token, 
                    $secret, 
                    $meta['role']);
            }
            
            //load global actions
            return $self->validateGlobal(
                $request, 
                $response, 
                $token, 
                $secret, 
                $meta['role']);
        };    
    }
    
    /**
     * Loops through routes trying to find the right one
     *
     * @param Eden\Registry\Index
     * @return array
     */
    public function getRoute($request) 
    {
        $path = $request->get('path', 'string');
        
        //find the route
        foreach($this->routes as $pattern => $meta) {
            $regex = str_replace('**', '!!', $pattern);
            $regex = str_replace('*', '([^/]*)', $regex);
            $regex = str_replace('!!', '(.*)', $regex);
            
            $regex = '#^'.$regex.'(.*)#';
            
            if(!preg_match($regex, $path, $matches)) {
                continue;
            }
            
            //get dynamic variables
            $variables = $this->getVariables($pattern, $path);
            
            return array($pattern, $meta, $variables);
        }
        
        return array(false, false, false);
    }
    
    /**
     * Checks permissions via database
     *
     * @param Eden\Registry\Index
     * @param Eden\Registry\Index
     * @param string
     * @param string
     * @param string
     * @return array
     */
    public function validateGlobal(
        $request, 
        $response,
        $token,
        $secret,
        $role
    ) {
        //if anything else
        //retreive the permissions based on the app token and app secret
        $search = eve()
            ->database()
            ->search('app')
            ->setColumns(
                'profile.*', 
                'app.*')
            ->innerJoinOn(
                'app_profile', 
                'app_profile_app = app_id')
            ->innerJoinOn(
                'profile', 
                'app_profile_profile = profile_id')
            ->filterByAppToken($token);
            
        if(isset($meta['role'])) {
            $search->addFilter(
                'app_permissions LIKE %s', 
                '%' . $role . '%');
        }
        
        if($secret) {
            $search->filterByAppSecret($secret);
        }
        
        $row = $search->getRow();
        
        if(empty($row)) {
            //don't allow
            return $this->fail($request, $response);
        }
        
        $request->set('source', $row);
        $request->set('source', 'access_token', $token);
        $request->set('source', 'access_secret', $secret);
    }
    
    /**
     * Checks permissions via database
     *
     * @param Eden\Registry\Index
     * @param Eden\Registry\Index
     * @param string
     * @param string
     * @param string
     * @return array
     */
    public function validateUser(
        $request, 
        $response,
        $token,
        $secret,
        $role
    ) {
        //retreive the permissions based on the session token and session secret
        $search = eve()
            ->database()
            ->search('session')
            ->setColumns(
                'session.*', 
                'profile.*', 
                'app.*')
            ->innerJoinOn(
                'session_app', 
                'session_app_session = session_id')
            ->innerJoinOn(
                'app', 
                'session_app_app = app_id')
            ->innerJoinOn(
                'session_auth', 
                'session_auth_session = session_id')
            ->innerJoinOn(
                'auth_profile', 
                'auth_profile_auth = session_auth_auth')
            ->innerJoinOn(
                'profile', 
                'auth_profile_profile = profile_id')
            ->filterBySessionToken($token)
            ->filterBySessionStatus('ACCESS')
            ->addFilter(
                'session_permissions LIKE %s', 
                '%' . $role . '%');
        
        if($secret) {
            $search->filterBySessionSecret($secret);
        }
        
        $row = $search->getRow();
        
        if(empty($row)) {
            //don't allow
            return $this->fail($request, $response);
        }
        
        $request->set('source', $row);
        $request->set('source', 'access_token', $token);
        $request->set('source', 'access_secret', $secret);
        
        $originalPath = $request->get('path', 'string');
        $newPath = str_replace('/rest/user', '/rest', $originalPath);
        
        $request->set('path', 'string', $newPath);
        $request->set('path', 'array', explode('/', $newPath));
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