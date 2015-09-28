<?php //-->
return array(
	'singular' 	=> 'App',	//for pages and messages
    'plural' 	=> 'Apps',	//for pages and messages
	'rest' => array(),
	'page' => array(
		'create',		//add a App/Create Action 
		'update',		//add a App/Update Action 
		'remove',		//add a App/Remove Action 
		'restore',		//add a App/Restore Action 
		'search',		//add a App/Search Action 
	), 				
	'model' => array(
		'create',		//add a App/Create Model 
		'update',		//add a App/Update Model
		'remove',		//add a App/Remove Model
		'restore',		//add a App/Restore Model
		'search',		//add a App/Search Model
		'detail',		//add a App/Detail Model
		'index',		//add a App/Index Model
	),			
	'permissions' => 'profile',	//session or source must have a linked profile_id
	'relations' => array(
		'profile' => false,
	),
	'job'	=> array(			//Jobs can be created with the following instructions
		'create' => array(		//add a App/Create Job
			array('create'	, 'app'),		//- create app
			array('link'	, 'profile'),	//- link profile
		),
			
		'update' => array(		//add a App/Update Job
			array('update'	, 'app'),	//- update app
		),
		
		'remove' => array(		//add a App/Remove Job
			array('remove'	, 'app')	//- remove app
		),
		
		'restore' => array(		//add a App/Restore Job
			array('restore'	, 'app')	//- restore app
		),
		
		'refresh' => array(		//add a App/Refresh Job
			array('refresh'	, 'app')	//- refresh app
		)
	),
	'fields' => array(
        'app_name' => array(
            'label' => 'Name',
            'type' => 'string',
            'field' => 'text',
            'holder' => 'Openovate Labs App',
            'valid' => 'required', 
            'search' => true
        ),
		'app_domain' => array(
            'label' => 'Domain',
            'type' => 'string',
            'field' => 'text',
            'holder' => '*.openovate.com',
            'search' => true
        ),
		'app_website' => array(
            'label' => 'Domain',
            'type' => 'string',
            'field' => 'text',
            'holder' => 'http://openovate.com/',
            'search' => true
        ),
		'app_permissions' => array(
            'label' => 'Permissions',
            'type' => 'string',
            'field' => 'checkbox',
            'valid' => 'required',
			'options' => array(
				array(
					'value' => 'public_profile',
					'label' => 'Public profiles',
            		'description' => 'Get profile detail'
				),
				array(
					'value' => 'public_sso',
					'label' => 'Single Sign On',
            		'description' => 'Use our Single Sign On'
				),
				array(
					'value' => 'personal_profile',
					'label' => 'Personal profile',
            		'description' => 'Access to your personal profile'
				),
				array(
					'value' => 'user_profile',
					'label' => 'User Profiles',
            		'description' => 'Access to others personal profile'
				),
				array(
					'value' => 'global_profile',
					'label' => 'Global Profiles',
            		'description' => 'Access to others profile with no permissions'
				)
			) 
        ),
		'app_token' => array(
            'label' => 'Token',
            'type' => 'string',
            'field' => false,
			'encode' => 'uuid'
        ),
		'app_secret' => array(
            'label' => 'Secret',
            'type' => 'string',
            'field' => false,
			'encode' => 'uuid'
        )
    ),
	'fixture'  => array()
);