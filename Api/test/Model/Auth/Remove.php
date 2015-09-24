<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

class ApiModelAuthRemoveTest extends PHPUnit_Framework_TestCase
{
    public function testRemoveAuth() 
	{
		$auth = eve()->registry()->get('test', 'auth');

        $row = eve()
        	->model('auth')
        	->remove()
        	->process(array( 
				'auth_id' => $auth['auth_id']));

		$this->assertEquals(0, $row['auth_active']);
    }
}