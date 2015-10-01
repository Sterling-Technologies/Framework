<?php //-->
return array(
	'roles' => array(
		'public_profile' => array(
            'title' => 'Profiles',
            'description' => 'Get a profile detail from a buyer or seller',
            'icon' => 'user'
        ),
        'public_sso' => array(
            'title' => 'Single Sign On',
            'description' => 'Use our Single Sign On',
            'icon' => 'lock'
        ),
		'personal_profile' => array(
            'title' => 'Profile',
            'description' => 'Access user profile',
            'icon' => 'user'
        ),
		'user_profile' => array(
            'title' => 'Profile',
            'description' => 'Access user profile',
            'icon' => 'user'
        ),
		'global_profile' => array(
            'title' => 'Profile',
            'description' => 'Access all profiles',
            'icon' => 'user'
        )
	),
    'dialog' => array(
        '/dialog/login' => array(
            'method' => 'ALL',
            'role' => 'public_sso'
        ),
        '/dialog/request' => array(
            'method' => 'ALL',
            'role' => 'public_sso'
        ),
        '/dialog/create' => array(
            'method' => 'ALL',
            'role' => 'public_sso'
        ),
        '/dialog/update' => array(
            'method' => 'ALL',
            'role' => 'public_sso'
        ),
        '/dialog/permissions' => array(
            'method' => 'ALL',
            'role' => 'public_sso'
        ),
        '/dialog/logout' => array(
            'method' => 'GET',
            'role' => 'public_sso'
        )
    ),
    'rest' => array(
        '/rest/profile/search' => array(
            'method' => 'GET',
            'role' => 'public_profile'
        ),
        '/rest/profile/detail/*' => array(
            'method' => 'GET',
            'role' => 'public_profile'
        ),
        '/rest/access' => array(
            'method' => 'POST',
            'role' => 'public_sso'
        ),
        '/rest/user/profile/detail' => array(
            'method' => 'GET',
            'role' => 'user_profile'
        ),
        '/rest/user/profile/update' => array(
            'method' => 'PUT',
            'role' => 'user_profile'
        ),
        '/rest/profile/detail' => array(
            'method' => 'GET',
            'role' => 'personal_profile'
        ),
        '/rest/profile/update' => array(
            'method' => 'PUT',
            'role' => 'personal_profile'
        )
    )
);