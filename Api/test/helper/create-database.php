<?php //-->
//not cool to just run it..
return function() {
	//Bootstrap
	eve()
		->defaultPaths()
		->defaultDebugging()
		->defaultTimezone('Asia/Manila');
	
	//get test configs
	$config = eve()->settings('test');
	
	//create the test db
	eve()->defaultDatabases($config['database']);
	eve()->database('build')->query('DROP DATABASE IF EXISTS `'.$config['database']['test']['name'].'`');
	eve()->database('build')->query('CREATE DATABASE `'.$config['database']['test']['name'].'`');
	
	//get schema
	$schema = eve('file', eve()->path('root').'/schema.sql')->getContent();
	
	//add queries
	$queries = explode(';', $schema);
	
	$queries[] = "INSERT INTO `app` (
		`app_id`, 
		`app_name`, 
		`app_domain`, 
		`app_token`, 
		`app_secret`, 
		`app_permissions`, 
		`app_website`, 
		`app_active`, 
		`app_type`, 
		`app_flag`, 
		`app_created`, 
		`app_updated`
	) VALUES (
		1, 
		'Main Application', 
		'*.openovate.com', 
		'".$config['app_token']."', 
		'".$config['app_secret']."', 
		'".implode(',', $config['scope'])."', 
		'http://openovate.com/', 
		1, NULL, 0, '2015-08-21 00:00:00', '2015-08-21 00:00:00'
	)";
	
	$queries[] = "INSERT INTO `auth` (
		`auth_id`, 
		`auth_slug`, 
		`auth_password`, 
		`auth_token`, 
		`auth_secret`, 
		`auth_permissions`, 
		`auth_facebook_token`, 
		`auth_facebook_secret`, 
		`auth_linkedin_token`, 
		`auth_linkedin_secret`, 
		`auth_twitter_token`, 
		`auth_twitter_secret`, 
		`auth_google_token`, 
		`auth_google_secret`, 
		`auth_active`, 
		`auth_type`, 
		`auth_flag`, 
		`auth_created`, 
		`auth_updated`
	) VALUES (
		1, 
		'admin@openovate.com', 
		MD5('admin'), 
		'".$config['app_token']."', 
		'".$config['app_secret']."', 
		'".implode(',', $config['scope'])."', 
		NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, 
		'2015-09-11 23:05:17', '2015-09-11 23:05:17'
	)";
	
	$queries[] = "INSERT INTO `profile` (
		`profile_id`, 
		`profile_name`, 
		`profile_email`, 
		`profile_phone`, 
		`profile_detail`, 
		`profile_company`, 
		`profile_job`, 
		`profile_gender`, 
		`profile_birth`, 
		`profile_website`, 
		`profile_facebook`, 
		`profile_linkedin`, 
		`profile_twitter`, 
		`profile_google`, 
		`profile_active`, 
		`profile_type`, 
		`profile_flag`, 
		`profile_created`, 
		`profile_updated`
	) VALUES (
		1, 
		'Admin', 
		'admin@openovate.com', 
		'+63 (2) 654-5110', 
		NULL, NULL, NULL, NULL, NULL, 
		NULL, NULL, NULL, NULL, NULL, 1, NULL, 
		0, '2015-09-11 23:05:16', '2015-09-11 23:05:16')";
	
	$queries[] = "INSERT INTO `app_profile` (`app_profile_app`, `app_profile_profile`) VALUES (1, 1)";
	$queries[] = "INSERT INTO `auth_profile` (`auth_profile_auth`, `auth_profile_profile`) VALUES (1, 1)";
	
	//now call the queries
	foreach($queries as $query) {
		$lines = explode("\n", $query);
		
		foreach($lines as $i => $line) {
			if(strpos($line, '--') === 0 || !trim($line)) {
				unset($lines[$i]);
				continue;
			}
		}
		
		$query = trim(implode("\n", $lines));
		
		if(!$query) {
			continue;
		}
		
		eve()->database()->query($query);
	}
};