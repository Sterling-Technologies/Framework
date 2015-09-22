<?php //-->
return array(
	'database'		=> array(	
		'test' => array(
			'host' 		=> '127.0.0.1',
			'name' 		=> 'testing_db',
			'user' 		=> 'root',
			'pass' 		=> '',
			'type' 		=> 'mysql',
			'default' 	=> true),
		'build' => array(
			'host' 		=> '127.0.0.1',
			'name' 		=> '',
			'user' 		=> 'root',
			'pass' 		=> '',
			'type' 		=> 'mysql',
			'default' 	=> false)
	),
	'app_token' 	=> '986e7ce6bec660838491c1cd0a1f4ef6',
	'app_secret' 	=> 'ba0d2fc7aab09dfa3463943c0aaa8551',
	'scope' => array(
		'public_profile',
		'public_sso',
		'personal_profile',
		'user_profile'
	)
);