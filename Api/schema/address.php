<?php //-->
return array(
	'singular' 	=> 'Address',	//for pages and messages
    'plural' 	=> 'Addresses',	//for pages and messages
	'rest' => array(
		'create',		//add a Rest/Address/Create Action 
		'update',		//add a Rest/Address/Update Action
		'remove',		//add a Rest/Address/Remove Action
		'restore',		//add a Rest/Address/Restore Action
		'search',		//add a Rest/Address/Search Action
		'detail'		//add a Rest/Address/Detail Action
	),
	'page' => array(
		'create',		//add a Address/Create Action 
		'update',		//add a Address/Update Action 
		'remove',		//add a Address/Remove Action 
		'restore',		//add a Address/Restore Action 
		'search',		//add a Address/Search Action 
		'detail'		//add a Address/Detail Action 
	), 				
	'model' => array(
		'create',		//add a Address/Create Model 
		'update',		//add a Address/Update Model
		'remove',		//add a Address/Remove Model
		'restore',		//add a Address/Restore Model
		'search',		//add a Address/Search Model
		'detail',		//add a Address/Detail Model
		'index',		//add a Address/Index Model
	),			
	'permissions' => 'profile',	//session or source must have a linked profile_id
	'relations' => array(),
	'job'	=> array(			//Jobs can be created with the following instructions
		'create' => array(		//add a Address/Create Job
			array('create'		, 'address'),	//- create address
			array('link'		, 'profile'),	//- link profile
		),
			
		'update' => array(		//add a Address/Update Job
			array('update'		, 'address'),	//- update address
		),
		'remove' => array(		//add a Address/Remove Job
			array('remove'		, 'address')	//- remove address
		),
		'restore' => array(		//add a Address/Restore Job
			array('restore'		, 'address')	//- restore address
		)
	),
	'fields' => array(
        'address_label' => array(
            'label' => 'Label',
            'type' => 'string',
            'field' => 'text',
            'holder' => 'My Home',
            'valid' => 'required', 
            'search' => true
        ),
		'address_phone' => array(
            'label' => 'Phone',
            'type' => 'string',
            'field' => 'text',
            'holder' => '+63 (999) 555-2424',
            'valid' => 'required'
        ),
		'address_street' => array(
            'label' => 'Street',
            'type' => 'string',
            'field' => 'text',
            'holder' => '123 Sesame Street',
            'valid' => 'required'
        ),
		'address_city' => array(
            'label' => 'City',
            'type' => 'string',
            'field' => 'text',
            'holder' => 'Makati City', 
            'search' => true,
            'valid' => 'required'
        ),
		'address_state' => array(
            'label' => 'State/Province',
            'type' => 'string',
            'field' => 'text',
            'holder' => 'Metro Manila', 
            'search' => true,
            'valid' => 'required'
        ),
		'address_country' => array(
            'label' => 'Country',
            'type' => 'string',
            'field' => 'text', 
            'search' => true
        ),
		'address_postal' => array(
            'label' => 'Postal',
            'type' => 'string',
            'field' => 'text',
            'holder' => '1550',
            'valid' => 'required'
        )
    ),
	'fixture'  => array(							//default rows to be inserted
		array(
			'address_label' => 'My Home',	
			'address_phone' => '+63 (999) 555-2121',
			'address_street' => '123 Sesame Street',
			'address_city' => 'Makati City', 
			'address_state' => 'Metro Manila', 
			'address_country' => 'PH',
			'address_postal' => '1551',
			'address_active' => '1',
			'address_created' => '2015-08-01 00:00:00',
			'address_updated' => '2015-09-01 00:00:00'
		),
		array(
			'address_label' => 'My Work',	
			'address_phone' => '+63 (999) 555-2222',
			'address_street' => '234 Sesame Street',
			'address_city' => 'Quezon City', 
			'address_state' => 'Metro Manila', 
			'address_country' => 'PH',
			'address_postal' => '1552',
			'address_active' => '1',
			'address_created' => '2015-08-02 00:00:00',
			'address_updated' => '2015-09-02 00:00:00'
		),
		array(
			'address_label' => 'My Office',	
			'address_phone' => '+63 (999) 555-2323',
			'address_street' => '345 Sesame Street',
			'address_city' => 'Mandaluyong City', 
			'address_state' => 'Metro Manila', 
			'address_country' => 'PH',
			'address_postal' => '1553',
			'address_active' => '1',
			'address_created' => '2015-08-03 00:00:00',
			'address_updated' => '2015-09-03 00:00:00'
		),
		array(
			'address_label' => 'My Moms House',	
			'address_phone' => '+63 (999) 555-2424',
			'address_street' => '456 Sesame Street',
			'address_city' => 'Makati City', 
			'address_state' => 'Metro Manila', 
			'address_country' => 'PH',
			'address_postal' => '1554',
			'address_active' => '1',
			'address_created' => '2015-08-04 00:00:00',
			'address_updated' => '2015-09-04 00:00:00'
		),
		array(
			'address_label' => 'Fred',	
			'address_phone' => '+63 (999) 555-2525',
			'address_street' => '567 Sesame Street',
			'address_city' => 'Mandaluyong City', 
			'address_state' => 'Metro Manila', 
			'address_country' => 'PH',
			'address_postal' => '1555',
			'address_active' => '1',
			'address_created' => '2015-08-05 00:00:00',
			'address_updated' => '2015-09-05 00:00:00'
		),
		array(
			'address_label' => 'My Home',	
			'address_phone' => '+63 (999) 555-2626',
			'address_street' => '678 Sesame Street',
			'address_city' => 'Pasig City', 
			'address_state' => 'Metro Manila', 
			'address_country' => 'PH',
			'address_postal' => '1556',
			'address_active' => '1',
			'address_created' => '2015-08-06 00:00:00',
			'address_updated' => '2015-09-06 00:00:00'
		),
		array(
			'address_label' => 'My Work',	
			'address_phone' => '+63 (999) 555-2727',
			'address_street' => '789 Sesame Street',
			'address_city' => 'Pasig City', 
			'address_state' => 'Metro Manila', 
			'address_country' => 'PH',
			'address_postal' => '1557',
			'address_active' => '1',
			'address_created' => '2015-08-07 00:00:00',
			'address_updated' => '2015-09-07 00:00:00'
		)
	)
);