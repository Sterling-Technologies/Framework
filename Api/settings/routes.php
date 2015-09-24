<?php //-->
return array(
    'developer' => array(
        '/create' => array(
            'method' => 'ALL'
        ),
        '/update' => array(
            'method' => 'ALL'
        ),
        '/login' => array(
            'method' => 'ALL'
        ),
        '/logout' => array(
            'method' => 'GET'
        ),
        '/app/list' => array(
            'method' => 'GET'
        ),
        '/app/create' => array(
            'method' => 'ALL'
        ),
        '/app/update/ =>id' => array(
            'method' => 'ALL'
        ),
        '/app/remove/ =>id' => array(
            'method' => 'GET'
        ),
        '/app/refresh/ =>id' => array(
            'method' => 'GET'
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
        '/rest/profile/list' => array(
            'method' => 'GET',
            'role' => 'public_profile'
        ),
        '/rest/profile/detail/ =>id' => array(
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