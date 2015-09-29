<?php //-->
return array(
	'singular' 	=> 'Sink',		//for pages and messages
    'plural' 	=> 'Dishes',	//for pages and messages
	'rest' => array(
		'create',		//add a Rest/Sink/Create Action 
		'update',		//add a Rest/Sink/Update Action
		'remove',		//add a Rest/Sink/Remove Action
		'restore',		//add a Rest/Sink/Restore Action
		'search',		//add a Rest/Sink/Search Action
		'detail'		//add a Rest/Sink/Detail Action
	),
	'page' => array(
		'create',		//add a Sink/Create Action 
		'update',		//add a Sink/Update Action 
		'remove',		//add a Sink/Remove Action 
		'restore',		//add a Sink/Restore Action 
		'search'		//add a Sink/Search Action 
	), 				
	'model' => array(
		'create',		//add a Sink/Create Model 
		'update',		//add a Sink/Update Model
		'remove',		//add a Sink/Remove Model
		'restore',		//add a Sink/Restore Model
		'search',		//add a Sink/Search Model
		'detail',		//add a Sink/Detail Model
		'index',		//add a Sink/Index Model
	),			
	'permissions' => 'profile',	//session or source must have a linked profile_id
	'relations' => array(
		'profile'	=> false,
		'app'		=> false,
		'file'		=> true
	),
	'job'	=> array(			//Jobs can be created with the following instructions
		'create' => array(		//add a Sink/Create Job
			array('create'		, 'sink'),		//- create sink
			array('link'		, 'profile'),	//- link profile
			array('link'		, 'app'),		//- link app
			array('linkAll'		, 'file'),		//- link all files
		),
			
		'update' => array(		//add a Sink/Update Job
			array('update'		, 'sink'),	//- update address
			array('unlinkAll'	, 'file'),		//- unlink all file
			array('linkAll'		, 'file'),		//- link all file
		),
		'remove' => array(		//add a Sink/Remove Job
			array('remove'		, 'sink')	//- remove address
		),
		'restore' => array(		//add a Sink/Restore Job
			array('restore'		, 'sink')	//- restore address
		),
		'custom' => array(		//add a Sink/Restore Job
			array('update'		, 'sink'),	//- update address
			array('unlinkAll'	, 'file'),		//- unlink all file
			array('linkAll'		, 'file'),		//- link all file
		)
	),
	'fields' => array(
        'sink_text' => array(
            'label' => 'Text Example',
            'type' => 'string',
            'field' => 'text',
            'holder' => 'Sample Text',
            'valid' => 'required', 
            'search' => true,
			'default' => 'sample 123'
        ),
        'sink_password' => array(
            'label' => 'Password Example',
            'type' => 'string',
            'field' => 'password',
            'holder' => 'Sample Password',
            'valid' => 'required',
			'encoding' => 'md5'
        ),
        'sink_token' => array(
            'label' => 'Token',
            'type' => 'string',
            'field' => false,
			'encoding' => 'uuid'
        ),
        'sink_date' => array(
            'label' => 'Date Example',
            'type' => 'datetime',
            'field' => 'date',
			'default' => '+30 days'
        ),
        'sink_alphanum' => array(
            'label' => 'Alpha Numeric Example',
            'type' => 'string',
            'field' => 'text',
			'valid' => 'alphanum-_'
        ),
        'sink_email' => array(
            'label' => 'Email Example',
            'type' => 'string',
            'field' => 'email'
        ),
        'sink_color' => array(
            'label' => 'Color Example',
            'type' => 'string',
            'field' => 'color',
            'valid' => 'hex'
        ),
        'sink_file' => array(
            'label' => 'File Example',
            'type' => 'string',
            'field' => array('file', 'accept' => 'image/*'),
			'valid' => 'required'
        ),
        'sink_cc' => array(
            'label' => 'Credit Card Example',
            'type' => 'string',
            'field' => 'text',
			'valid' => array('empty', 'cc')
        ),
        'sink_html' => array(
            'label' => 'HTML Example',
            'type' => 'text',
            'field' => 'textarea',
			'valid' => 'html'
        ),
        'sink_url' => array(
            'label' => 'URL Example',
            'type' => 'string',
            'field' => 'text',
			'valid' => 'url'
        ),
        'sink_regex' => array(
            'label' => 'RegExp Example',
            'type' => 'string',
            'field' => 'text',
			'valid' => array(array('regex', '/[0-9]\-chris/'))
        ),
		'sink_select' => array(
            'label' => 'Select Example',
            'type' => 'string',
            'field' => 'select',
            'valid' => 'required',
			'options' => array(
				array (
					'value' => 'choice1',
					'label' => 'Choice 1'
				),
				array (
					'value' => 'choice2',
					'label' => 'Choice 2'
				),
				array (
					'value' => 'choice3',
					'label' => 'Choice 3'
				),
				array (
					'value' => 'choice4',
					'label' => 'Choice 4'
				),
				array (
					'value' => 'choice5',
					'label' => 'Choice 5'
				)
			)
        ),
		'sink_checkboxes' => array(
            'label' => 'Checkboxes Example',
            'type' => 'string',
            'field' => 'checkbox',
            'valid' => 'required',
			'options' => array(
				array (
					'value' => 'choice1',
					'label' => 'Choice 1'
				),
				array (
					'value' => 'choice2',
					'label' => 'Choice 2'
				),
				array (
					'value' => 'choice3',
					'label' => 'Choice 3'
				),
				array (
					'value' => 'choice4',
					'label' => 'Choice 4'
				),
				array (
					'value' => 'choice5',
					'label' => 'Choice 5'
				)
			)
        ),
		'sink_radios' => array(
            'label' => 'Radios Example',
            'type' => 'string',
            'field' => 'radio',
            'valid' => 'required',
			'options' => array(
				array (
					'value' => 'choice1',
					'label' => 'Choice 1'
				),
				array (
					'value' => 'choice2',
					'label' => 'Choice 2'
				),
				array (
					'value' => 'choice3',
					'label' => 'Choice 3'
				),
				array (
					'value' => 'choice4',
					'label' => 'Choice 4'
				),
				array (
					'value' => 'choice5',
					'label' => 'Choice 5'
				)
			),
			'default' => 'choice3'
        ),
		'sink_bool' => array(
            'label' => 'Boolean Example',
            'type' => 'bool',
            'field' => 'checkbox',
			'default' => 1
        ),
		'sink_small' => array(
            'label' => 'Small Example',
            'type' => 'small',
            'field' => 'text',
			'default' => 0
        ),
		'sink_float' => array(
            'label' => 'Float Example',	
            'type' => 'float',				
            'field' => array(					
				'number',
				'min' => 0,
				'step' => 0.01
			), 		
            'holder' => '9999.99',					
            'valid' => 'required',					
            'search' => true,
			'default' => '0.00'
        ),
		'sink_int' => array(
            'label' => 'Int Example',	
            'type' => 'int',				
            'field' => 'number', 	
			'default' => '1'
        ),
		
    ),
	'fixture'  => array(							//default rows to be inserted
		array(
			'sink_text' => 'Text 1',
			'sink_password' => 'admin',
			'sink_date' => '2015-04-10',
			'sink_alphanum' => 'asd-asd-1',
			'sink_email' => 'cblanquera@mailinator.com',
			'sink_color' => '345344',
			'sink_file' => 'somthign',
			'sink_cc' => '4111-1111-1111-1111',
			'sink_html' => '<p>Awesome 1</p>',
			'sink_url' => 'http://someexample1.com',
			'sink_regex' => '1-chris',
			'sink_select' => 'choice2',
			'sink_checkboxes' => 'choice3,choice4',
			'sink_radios' => 'choice1',
			'sink_bool' => 1,
			'sink_small' => 4,
			'sink_float' => 1.1,
			'sink_int' => 1
		),
		array(
			'sink_text' => 'Text 2',
			'sink_password' => 'admin',
			'sink_date' => '2015-04-11',
			'sink_alphanum' => 'asd-asd-2',
			'sink_email' => 'cblanquera2@mailinator.com',
			'sink_color' => '345342',
			'sink_file' => 'somthign',
			'sink_cc' => '4111-1111-1121-1111',
			'sink_html' => '<p>Awesome 2</p>',
			'sink_url' => 'http://someexample2.com',
			'sink_regex' => '2-chris',
			'sink_select' => 'choice2',
			'sink_checkboxes' => 'choice3,choice4',
			'sink_radios' => 'choice1',
			'sink_bool' => 1,
			'sink_small' => 2,
			'sink_float' => 2.2,
			'sink_int' => 2
		),
		array(
			'sink_text' => 'Text 3',
			'sink_password' => 'admin',
			'sink_date' => '2015-04-13',
			'sink_alphanum' => 'asd-asd-3',
			'sink_email' => 'cblanquera3@mailinator.com',
			'sink_color' => '345343',
			'sink_file' => 'somthign',
			'sink_cc' => '4111-1111-1131-1111',
			'sink_html' => '<p>Awesome 3</p>',
			'sink_url' => 'http://someexample3.com',
			'sink_regex' => '3-chris',
			'sink_select' => 'choice2',
			'sink_checkboxes' => 'choice3,choice4',
			'sink_radios' => 'choice1',
			'sink_bool' => 1,
			'sink_small' => 3,
			'sink_float' => 3.3,
			'sink_int' => 3
		)
	)
);