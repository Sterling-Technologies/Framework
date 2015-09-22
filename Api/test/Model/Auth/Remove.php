<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\Auth;

class Remove extends PHPUnit_Framework_TestCase
{
    public function testRemoveAuth() 
	{
		$auth = control()->registry()->get('test', 'auth');

        $row = control()
        	->model('auth')
        	->remove()
        	->process(array( 
				'auth_id' => $auth['auth_id']));

        //TODO
		// $this->assertEquals(null, error);
    }
}