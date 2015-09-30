<?php //-->
/*
 * This file is part of the Persistent package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace Api\Plugin;

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
class Rest extends Base 
{
	const FAIL_401 = 'Invalid Request';
	
	/**
	 * Main plugin method
	 *
	 * @return function
	 */
	public function import() 
	{
		$self = $this;
		return function($request, $response) use ($self) {
			//set the header
			$response->set('headers', 'Content-Type', 'text/json');
		
			$routes = $this->settings('routes');
			$method = $request->get('method');
			$path = $request->get('path', 'string');
			
			//find the route
			$found = false;
			foreach($routes['rest'] as $pattern => $meta) {
				$regex = str_replace('**', '!!', $pattern);
				$regex = str_replace('*', '([^/]*)', $regex);
				$regex = str_replace('!!', '(.*)', $regex);
				
				$regex = '#^'.$regex.'(.*)#';
				if(!preg_match($regex, $path, $matches)) {
					continue;
				}
				
				$found = true;
				break;
			}
			
			//if no path was found
			if(!$found) {
				//don't allow
				return $self->fail($request, $response);
			}
			
			//get dynamic variables
			$variables = $this->getVariables($matches);
			
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
			
			//if user
			if(isset($meta['role']) && strpos($meta['role'], 'user_') === 0) {
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
						'%' . $meta['role'] . '%');
				
				if($secret) {
					$search->filterBySessionSecret($secret);
				}
				
				$row = $search->getRow();
				
				if(empty($row)) {
					//don't allow
					return $self->fail($request, $response);
				}
				
				$request->set('source', $row);
				$request->set('source', 'access_token', $token);
				$request->set('source', 'access_secret', $secret);
				
				$originalPath = $request->get('path', 'string');
				$newPath = str_replace('/rest/user', '/rest', $originalPath);
				
				$request->set('path', 'string', $newPath);
				$request->set('path', 'array', explode('/', $newPath));
				return;
			}
			
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
					'%' . $meta['role'] . '%');
			}
			
			if($secret) {
				$search->filterByAppSecret($secret);
			}
			
			$row = $search->getRow();
			
			if(empty($row)) {
				//don't allow
				return $self->fail($request, $response);
			}
			
			$request->set('source', $row);
			$request->set('source', 'access_token', $token);
			$request->set('source', 'access_secret', $secret);
		};	
	}
	
	/**
	 * Returns a dynamic list of variables
	 * based on the given pattern and path
	 *
	 * @return array
	 */
	protected function getVariables($matches) 
	{
		$variables = array();
		
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
	
	public function fail($request, $response) {
		$response->set('body', json_encode(array( 
			'error' => true, 
			'message' => self::FAIL_401 ), 
		JSON_PRETTY_PRINT));
		
		return false;
	}
}