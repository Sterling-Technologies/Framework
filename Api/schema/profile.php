<?php //-->
return array(
	'singular' 	=> 'Profile',	//for pages and messages
    'plural' 	=> 'Profiles',	//for pages and messages
	'rest' => array( 
		'update',		//add a Rest/Profile/Update Action
		'search',		//add a Rest/Profile/Search Action
		'detail'		//add a Rest/Profile/Detail Action
	),
	'page' => array(
		'update',		//add a Profile/Update Action 
	), 				
	'model' => array(
		'create',		//add a Profile/Create Model 
		'update',		//add a Profile/Update Model
		'remove',		//add a Profile/Remove Model
		'restore',		//add a Profile/Restore Model
		'search',		//add a Profile/Search Model
		'detail',		//add a Profile/Detail Model
		'index',		//add a Profile/Index Model
	),
	'relations' => array(),
	'job'	=> array(			//Jobs can be created with the following instructions
		'update' => array(		//add a Profile/Update Job
			array('update'		, 'profile'),	//- update address
		),
	),
	'fields' => array(
        'profile_name' => array(
            'label' => 'Name',
            'type' => 'string',
            'field' => 'text',
            'holder' => 'John Doe',
            'valid' => 'required', 
            'search' => true
        ),
		'profile_email' => array(
            'label' => 'Email',
            'type' => 'string',
            'field' => 'email',
            'holder' => 'john@doe.com'
        ),
		'profile_phone' => array(
            'label' => 'Phone',
            'type' => 'string',
            'field' => 'text',
            'holder' => '555-2424'
        ),
		'profile_detail' => array(
            'label' => 'Bio',
            'type' => 'text',
            'field' => 'textarea',
            'holder' => 'I am awesome.'
        ),
        'profile_image' => array(
            'label' => 'Image',
            'type' => 'string',
            'field' => array('file', 'accept' => 'image/*')
        ),
		'profile_company' => array(
            'label' => 'Company',
            'type' => 'string',
            'field' => 'text'
        ),
		'profile_job' => array(
            'label' => 'Job',
            'type' => 'string',
            'field' => 'text'
        ),
		'profile_gender' => array(
            'label' => 'Gender',
            'type' => 'string',
            'field' => 'radio',
			'options' => array(
				array(
					'label' => 'Male',
					'value' => 'male'
				),
				array(
					'label' => 'Female',
					'value' => 'female'
				)
			)
        ),
		'profile_birth' => array(
            'label' => 'Birth',
            'type' => 'datetime',
            'field' => 'date'
        ),
		'profile_facebook' => array(
            'label' => 'Facebook',
            'type' => 'string',
            'field' => 'text',
			'valid' => 'url'
        ),
		'profile_linkedin' => array(
            'label' => 'LinkedIn',
            'type' => 'string',
            'field' => 'text',
			'valid' => 'url'
        ),
		'profile_twitter' => array(
            'label' => 'Twitter',
            'type' => 'string',
            'field' => 'text',
			'valid' => 'url'
        ),
		'profile_google' => array(
            'label' => 'Google',
            'type' => 'string',
            'field' => 'text',
			'valid' => 'url'
        ),
		'profile_reference' => array(
            'label' => 'Reference',
            'type' => 'string',
            'field' => false
        )
    ),
	'fixture'  => array()
);