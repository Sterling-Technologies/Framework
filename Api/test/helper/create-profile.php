<?php 
return function($settings) {
	//create auth
	$settings['profile'] = eve()->model('profile')
		->create()
		->process(array(
		'profile_name' => $settings['profile_name']
	))->get();

//create file
	
	$settings['file'] = eve()->model('file')
		->create()
		->process(array(
		'file_link' => $settings['file_link'],
		'file_type' => 'main_profile'
	))->get();

//link profile to file
				
	eve()->model('profile')
		->linkFile(
			$settings['profile']['profile_id'],
			$settings['file']['file_id']);
	
	return $settings;
};