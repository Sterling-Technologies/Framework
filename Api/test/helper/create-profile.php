<?php 
return function($settings) {
	//create auth
	$settings['profile'] = eve()->model('profile')
		->create()
		->process(array(
		'profile_name' => $settings['profile_name']
	))->get();
	
	return $settings;
};