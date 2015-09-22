<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\Session;

class Index extends PHPUnit_Framework_TestCase
{
    public function testGetProfileByToken() 
	{
		$session = control()->registry()->get('test', 'session');
		$profile = control()->registry()->get('test', 'profile');

        $row = control()
        	->model('session')
        	->getProfileByToken($session['access_token']);

		$this->assertEquals($profile['profile_name'], $row['profile_name']);
    }
}