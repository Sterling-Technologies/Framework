<?php //-->
return array(
	'singular' 	=> 'Product',	//for pages and messages
    'plural' 	=> 'Products',	//for pages and messages
	'rest' => array(
		'create',		//add a Rest/Product/Create Action 
		'update',		//add a Rest/Product/Update Action
		'remove',		//add a Rest/Product/Remove Action
		'restore',		//add a Rest/Product/Restore Action
		'search',		//add a Rest/Product/Search Action
		'detail'		//add a Rest/Product/Detail Action
	),
	'page' => array(
		'create',		//add a Product/Create Action 
		'update',		//add a Product/Update Action 
		'remove',		//add a Product/Remove Action 
		'restore',		//add a Product/Restore Action 
		'search',		//add a Product/Search Action 
		'detail'		//add a Product/Detail Action 
	), 				
	'model' => array(
		'create',		//add a Product/Create Model 
		'update',		//add a Product/Update Model
		'remove',		//add a Product/Remove Model
		'restore',		//add a Product/Restore Model
		'search',		//add a Product/Search Model
		'detail',		//add a Product/Detail Model
		'index',		//add a Product/Index Model
	),			
	//'permissions' => 'profile',	//session or source must have a linked profile_id
	'relations' => array(
		'file' 		=> true,	//one to many relationship
		'profile' 	=> false,	//one to one relationship
		'app' 		=> false	//one to one relationship
	),
	'job'	=> array(			//Jobs can be created with the following instructions
		'create' => array(		//add a Product/Create Job
			array('create'		, 'product'),	//- create product
			//array('create'	, 'profile'),	//- create profile
			array('link'		, 'profile'),	//- link profile
			array('linkAll'		, 'files'),		//- link all files
			array('link'		, 'app')		//- link app
		),
			
		'update' => array(		//add a Product/Update Job
			array('update'		, 'product'),	//- update product
			array('unlinkAll'	, 'file'),		//- unlink all files
			array('linkAll'		, 'file')		//- link all files
		),
		'remove' => array(		//add a Product/Remove Job
			array('remove'		, 'product')	//- remove product
		),
		'restore' => array(		//add a Product/Restore Job
			array('restore'		, 'product')	//- restore product
		)
	),
	'fields' => array(
        'product_title' => array(					
            'label' => 'Title',						// used for pages and messages
            'type' => 'string',						// used for database and validation
            'field' => 'text',						// used for form pages
            'holder' => 'Please enter a title',		// used for form pages
            'valid' => 'required',					// used in create/update actions
            'search' => true						// used in search actions
        ),	
        'product_detail' => array(					
            'label' => 'Detail',					// used for pages and messages
            'type' => 'text',						// used for database and validation
            'field' => 'textarea',					// used for form pages
            'holder' => 'Please enter a detail',	// used for form pages
            'valid' => 'required',					// used in create/update actions
            'search' => true						// used in search actions
        ),		
        'product_price' => array(
            'label' => 'Price',						// used for pages and messages
            'type' => 'float',						// used for database and validation
            'field' => array(						// used for form pages
				'number',
				'min' => 0,
				'step' => 0.01
			), 		
            'holder' => '9999.99',					// used for form pages
            'valid' => 'required',					// used in create/update actions
            'search' => true						// used in search actions
        ),
        'product_brand' => array(
            'label' => 'Brand',						// used for pages and messages
            'type' => 'string',						// used for database and validation
            'field' => 'text',						// used for form pages
            'holder' => 'Please enter a brand',		// used for form pages
            'search' => true						// used in search actions
        ),			
        'product_reference' => array(
            'label' => 'Reference',					// used for pages and messages
            'type' => 'string',						// used for database and validation
            'field' => 'text',						// used for form pages
            'holder' => 'Enter a reference',		// used for form pages
            'search' => true						// used in search actions
        ),
        'product_currency' => array(
            'label' => 'Currency',					// used for pages and messages
            'type' => 'string',						// used for database and validation
            'field' => false,						// used for form pages
			'default' => 'php'						// used in database and form fields
        ),
        'product_srp' => array(
            'label' => 'SRP',						// used for pages and messages
            'type' => 'float',						// used for database and validation
            'field' => array(						// used for form pages
				'number',
				'min' => 0,
				'step' => 0.01
			), 
            'holder' => '9999.99'					// used for form pages
        ),
        'product_expires' => array(
            'label' => 'Expires',					// used for pages and messages
            'type' => 'datetime',					// used for database and validation
            'field' => 'date',						// used for form pages
            'holder' => 'When does it expire ?',	// used for form pages
            'search' => true						// used in search actions
        )
    ),
	'fixture'  => array(							//default rows to be inserted
		array(
			'product_slug' => 'iphone-6-16gb',
			'product_brand' => 'Apple',
			'product_title' => 'iPhone 6 16GB',
			'product_detail' => 'Some Detail 1',
			'product_reference' => '123',
			'product_label' => 'cell phone,phone,iphone',
			'product_currency' => 'php',
			'product_srp' => '222.22',
			'product_price' => '111.11',
			'product_expires' => '2015-12-01 00:00:00',
			'product_active' => '1',
			'product_created' => '2015-08-01 00:00:00',
			'product_updated' => '2015-09-01 00:00:00'
		),
		array(
			'product_slug' => 'iphone-6-32gb',
			'product_brand' => 'Apple',
			'product_title' => 'iPhone 6 32GB',
			'product_detail' => 'Some Detail 2',
			'product_reference' => '234',
			'product_label' => 'cell phone,iphone',
			'product_currency' => 'php',
			'product_srp' => '333.33',
			'product_price' => '222.22',
			'product_expires' => '2015-12-02 00:00:00',
			'product_active' => '1',
			'product_created' => '2015-08-02 00:00:00',
			'product_updated' => '2015-09-02 00:00:00'
		),
		array(
			'product_slug' => 'asus-netbook-intel-core',
			'product_brand' => 'Asus',
			'product_title' => 'Asus Netbook Intel Core i5/32GB RAM/120GB HDD',
			'product_detail' => 'Some Detail 3',
			'product_reference' => '345',
			'product_label' => 'laptop,asus,netbook',
			'product_currency' => 'php',
			'product_srp' => '444.44',
			'product_price' => '333.33',
			'product_active' => '0',
			'product_created' => '2015-08-03 00:00:00',
			'product_updated' => '2015-09-03 00:00:00'
		),
		array(
			'product_slug' => 'summer-dress-by-forever-21',
			'product_brand' => 'Forever 21',
			'product_title' => 'Summer Dress By Forever 21',
			'product_detail' => 'Some Detail 4',
			'product_reference' => '456',
			'product_label' => 'dress,new arrival',
			'product_currency' => 'php',
			'product_srp' => '555.55',
			'product_price' => '444.44',
			'product_active' => '1',
			'product_created' => '2015-08-04 00:00:00',
			'product_updated' => '2015-09-04 00:00:00'
		),
		array(
			'product_slug' => 'spring-dress-by-bench',
			'product_brand' => 'Bench',
			'product_title' => 'Spring Dress By Bench',
			'product_detail' => 'Some Detail 5',
			'product_reference' => '567',
			'product_label' => 'dress,new arrival,discount',
			'product_currency' => 'php',
			'product_srp' => '666.66',
			'product_price' => '555.55',
			'product_expires' => '2015-12-05 00:00:00',
			'product_active' => '1',
			'product_created' => '2015-08-05 00:00:00',
			'product_updated' => '2015-09-05 00:00:00'
		),
		array(
			'product_slug' => 'hp-netbook-intel-core',
			'product_brand' => 'HP',
			'product_title' => 'HP Laptop Intel Core i5/4GB RAM/80GB HDD',
			'product_detail' => 'Some Detail 6',
			'product_reference' => '678',
			'product_label' => 'laptop,hp,netbook',
			'product_currency' => 'php',
			'product_price' => '666.66',
			'product_active' => '1',
			'product_created' => '2015-08-06 00:00:00',
			'product_updated' => '2015-09-06 00:00:00'
		),
		array(
			'product_slug' => 'apple-macbook-air-intel-core',
			'product_brand' => 'Apple',
			'product_title' => 'Apple Macbook Air Core i5/4GB RAM/80GB HDD',
			'product_detail' => 'Some Detail 6',
			'product_reference' => '890',
			'product_label' => 'laptop,new arrival',
			'product_currency' => 'php',
			'product_price' => '666.66',
			'product_active' => '1',
			'product_created' => '2015-08-07 00:00:00',
			'product_updated' => '2015-09-07 00:00:00'
		),
		array(
			'product_slug' => 'samsung-galaxy-tab-8gb',
			'product_brand' => 'Samsung',
			'product_title' => 'Samsung Galaxy Tab 8GB',
			'product_detail' => 'Some Detail 8',
			'product_reference' => '901',
			'product_label' => 'cell phone,galaxy',
			'product_currency' => 'php',
			'product_srp' => '888.88',
			'product_price' => '777.77',
			'product_expires' => '2015-12-07 00:00:00',
			'product_active' => '1',
			'product_created' => '2015-08-07 00:00:00',
			'product_updated' => '2015-09-07 00:00:00'
		),
		array(
			'product_slug' => 'samsung-galaxy-note-8gb',
			'product_brand' => 'Samsung',
			'product_title' => 'Samsung Galaxy Note 8GB',
			'product_detail' => 'Some Detail 9',
			'product_reference' => '012',
			'product_label' => 'cell phone,galaxy',
			'product_currency' => 'php',
			'product_srp' => '999.99',
			'product_price' => '888.88',
			'product_expires' => '2015-12-08 00:00:00',
			'product_active' => '1',
			'product_created' => '2015-08-08 00:00:00',
			'product_updated' => '2015-09-08 00:00:00'
		),
		array(
			'product_slug' => 'samsung-galaxy-note-16gb',
			'product_brand' => 'Samsung',
			'product_title' => 'Samsung Galaxy Note 16GB',
			'product_detail' => 'Some Detail 10',
			'product_label' => 'cell phone,galaxy',
			'product_currency' => 'php',
			'product_price' => '999.99',
			'product_expires' => '2015-12-10 00:00:00',
			'product_active' => '1',
			'product_created' => '2015-08-10 00:00:00',
			'product_updated' => '2015-09-10 00:00:00'
		),
		array(
			'product_slug' => 'winter-dress-by-bench',
			'product_brand' => 'Bench',
			'product_title' => 'Winter Dress By Bench',
			'product_detail' => 'Some Detail 11',
			'product_label' => 'dress,discount',
			'product_currency' => 'php',
			'product_srp' => '1100.11',
			'product_price' => '1000.10',
			'product_active' => '1',
			'product_created' => '2015-08-11 00:00:00',
			'product_updated' => '2015-09-11 00:00:00'
		),
		array(
			'product_slug' => 'fall-dress-by-bench',
			'product_brand' => 'Bench',
			'product_title' => 'Fall Dress By Bench',
			'product_detail' => 'Some Detail 12',
			'product_label' => 'dress,new arrival,discount',
			'product_currency' => 'php',
			'product_srp' => '1200.12',
			'product_price' => '1100.11',
			'product_active' => '1',
			'product_created' => '2015-08-12 00:00:00',
			'product_updated' => '2015-09-12 00:00:00'
		),
		array(
			'product_slug' => 'summer-dress-by-forever-21-2',
			'product_brand' => 'Forever 21',
			'product_title' => 'Summer Dress By Forever 21',
			'product_detail' => 'Some Detail 13',
			'product_label' => 'dress,new arrival',
			'product_currency' => 'php',
			'product_srp' => '1300.13',
			'product_price' => '1200.12',
			'product_active' => '1',
			'product_created' => '2015-08-13 00:00:00',
			'product_updated' => '2015-09-13 00:00:00'
		),
		array(
			'product_slug' => 'asus-netbook-intel-core-2',
			'product_brand' => 'Asus',
			'product_title' => 'Asus Netbook Intel Core i3/4GB RAM/120GB HDD',
			'product_detail' => 'Some Detail 14',
			'product_label' => 'asus,netbook',
			'product_currency' => 'php',
			'product_srp' => '1300.13',
			'product_price' => '1200.12',
			'product_active' => '0',
			'product_created' => '2015-08-14 00:00:00',
			'product_updated' => '2015-09-14 00:00:00'
		)
	)
);