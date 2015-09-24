<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

class ApiModelAppIndexTest extends PHPUnit_Framework_TestCase
{
    public function testCreateAndLinkProfile() 
	{
		$model = eve()
			->model('profile')
			->create()
			->process(array('profile_name' => 'TEST FOR APP'));

		eve()->registry()->set('test', 'profile', $model->get());

		$app = eve()->registry()->get('test', 'app');
		$profile = eve()->registry()->get('test', 'profile');

		$model = eve()
			->model('app')
			->linkProfile(
				$app['app_id'],
				$profile['profile_id']);

		$this->assertEquals(
			$app['app_id'],
			$model['app_profile_app']);

		$this->assertEquals(
			$profile['profile_id'],
			$model['app_profile_profile']);
    }
	
    public function testGetProfileByToken() 
	{	
		$app = eve()->registry()->get('test', 'app');

		$profile = eve()
			->model('app')
			->getProfileByToken($app['app_token']);

		$this->assertEquals('TEST FOR APP', $profile['profile_name']);
    }

    public function testApprovePermissions() 
    {	
    	$app = eve()->registry()->get('test', 'app');
    	$profile = eve()->registry()->get('test', 'profile');

		$yes = eve()
			->model('app')
			->permissions(
				$app['app_id'], 
				$profile['profile_id']);
		
		$this->assertTrue($yes);

		$yes = eve()
			->model('app')
			->permissions($app['app_id'], 222);

		$this->assertFalse($yes);
    }

    public function testUnlinkAndRemoveProfile() 
    {
    	$app = eve()->registry()->get('test', 'app');
    	$profile = eve()->registry()->get('test', 'profile');

		$model = eve()
			->model('app')
			->unlinkProfile(
				$app['app_id'],
				$profile['profile_id']);
		
		$this->assertEquals(
			$app['app_id'],
			$model['app_profile_app']);

		$this->assertEquals(
			$profile['profile_id'],
			$model['app_profile_profile']);
    }

}