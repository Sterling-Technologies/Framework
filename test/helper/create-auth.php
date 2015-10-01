<?php

return function($settings) {

	//create auth
	$config = eve()->settings('test');
	$settings['auth'] = eve()->model('auth')->create()->process(array(
		'auth_slug'			=> $settings['auth_slug'],
		'auth_permissions'	=> implode(',', $config['scope']),
		'auth_password'		=> $settings['auth_password'],
		'confirm'			=> $settings['auth_password'] 
	))->get();	


	//create profile
	if(isset($settings['profile'])) {
		return $settings;
	}
		
	$callback = require(__DIR__.'/create-profile.php');
	$settings = $callback($settings);

	//link profile to auth
	$r = eve()
		->model('auth')
		->linkProfile(
			$settings['auth']['auth_id'],
			$settings['profile']['profile_id']);

	return $settings;
};