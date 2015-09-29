<?php //-->
return array(
	'singular' 	=> 'File',	//for pages and messages
    'plural' 	=> 'Files',	//for pages and messages
	'model' => array(
		'create',		//add a Address/Create Model 
		'remove',		//add a Address/Remove Model
		'detail',		//add a Address/Detail Model
		'search',		//add a Address/Search Model
		'index',		//add a Address/Index Model
	),			
	'relations' => array(),
	'fields' => array(
        'file_link' => array(
            'label' => 'Link',
            'type' => 'string',
            'field' => false,
            'valid' => 'required'
        ),
		'file_path' => array(
            'label' => 'Path',
            'type' => 'string',
            'field' => false
        ),
		'file_mime' => array(
            'label' => 'Mime',
            'type' => 'string',
            'field' => false,
            'valid' => 'required'
        ),
    ),
	'fixture'  => array()
);