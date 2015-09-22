<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\Auth;

class Update extends PHPUnit_Framework_TestCase
{
    public function testUpdateAuth() 
	{
		$auth = control()->registry()->get('test', 'auth');
		
        $model = control()->model('auth')->update()->process(array(
			'auth_id' => $auth['auth_id'],
			'auth_facebook_token' => '1234567890'));

		$this->assertEquals('1234567890', $model['auth_facebook_token']);
    }
}