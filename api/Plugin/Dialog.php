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

use Api\Action\Dialog\Invalid;

/**
 * Validates dialog requests 
 *
 * @vendor Openovate
 * @package Framework
 * @author Christian Blanquera cblanquera@openovate.com
 */
class Dialog extends Base 
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
			$routes = $this->settings('routes');
			$method = $request->get('method');
			$path = $request->get('path', 'string');
			
			//find the route
			$found = false;
			foreach($routes['dialog'] as $pattern => $meta) {
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
			
			if($request->isKey('get', 'client_id')) {
				$token = $request->get('get', 'client_id');
			}
				
			//must have access token
			if(!$token) {
				//all dialogs must include an access token
				return $self->fail($request, $response);
			}
			
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
					->filterBySessionToken(token)
					->filterBySessionStatus('ACCESS')
					->addFilter(
						'session_permissions LIKE ?', 
						'%' + $meta['role'] . '%');
				
				$row = $search->getRow();
				
				if(empty($row)) {
					//don't allow
					return $self->fail($request, $response);
				}
				
				$request->set('source', $row);
				$request->set('source', 'access_token', $row['session_token']);
				$request->set('source', 'access_secret', $row['session_secret']);
				
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
			
			$row = $search->getRow();
			
			if(empty($row)) {
				//don't allow
				return $self->fail($request, $response);
			}
			
			$request->set('source', $row);
			$request->set('source', 'access_token', $row['app_token']);
			$request->set('source', 'access_secret', $row['app_secret']);
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
	
	public function fail($request, $response) 
	{
		$body = Invalid::i()
			->setRequest($request)
			->setResponse($response)
			->render();
		
		$response->set('body', $body);
		
		return false;
	}
}