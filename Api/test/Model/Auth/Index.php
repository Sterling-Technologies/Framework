<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

class ApiModelAuthIndexTest extends PHPUnit_Framework_TestCase
{
    public function testExist() 
	{
        $auth = eve()->registry()->get('test', 'auth');

		$total = eve()
			->model('auth')
			->exists($auth['auth_slug']);

		$this->assertEquals(1, $total);
    }
	
    public function testCreateAndLinkProfile() 
	{
        $model = eve()
        	->model('profile')
        	->create()
        	->process(array(
				'profile_name' => 'TEST FOR AUTH'));
		
		eve()->registry()->set('test', 'profile', $model->get());

        $profile = eve()->registry()->get('test', 'profile');
        $auth = eve()->registry()->get('test', 'auth');

		$model = eve()->model('auth')
			->linkProfile(
				$auth['auth_id'], 
				$profile['profile_id']);

		$this->assertEquals(
			$auth['auth_id'],
			$model['auth_profile_auth']);

		$this->assertEquals(
			$profile['profile_id'],
			$model['auth_profile_profile']);
    }

    public function testCreateAndUnlinkProfile() 
    {
    	$profile = eve()->registry()->get('test', 'profile');
        $auth = eve()->registry()->get('test', 'auth');

    	$model = eve()
    		->model('auth')
    		->unlinkProfile(
    			$auth['auth_id'], 
    			$profile['profile_id']);
    		
    	$this->assertEquals(
			$auth['auth_id'],
			$model['auth_profile_auth']);

		$this->assertEquals(
			$profile['profile_id'],
			$model['auth_profile_profile']);	
    }
}