<?php
return function($settings) {
	$config = eve()->settings('test');
	//create auth

	if(!isset($settings['auth'])) {
		$callback = include(__DIR__.'/create-auth.php');
		$settings = $callback($settings);
	}
	
	//get app
	$settings['app'] = eve()
		->model('app')
		->search()
		->process()
		->filterByAppId(1)
		->getRow();
		
	//request
	$settings['session'] = eve()
		->model('session')
		->request()
		->process(array(
			'app_id' => $settings['app']['app_id'],
			'auth_id' => $settings['auth']['auth_id'],
			'session_permissions' => implode(',', $config['scope'])
	));

	//access
	$settings['session'] = eve()
		->model('session')
		->access()
		->process(array(
			'client_id'		=> $settings['app']['app_token'],
			'client_secret'	=> $settings['app']['app_secret'],
			'code'			=> $settings['session']['session_token']
	));


	return $settings;
};