<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\App;

class Index extends PHPUnit_Framework_TestCase
{
    public function testCreateAndLinkProfile() 
	{
		$model = control()
			->model('profile')
			->create()
			->process(array('profile_name' => 'TEST FOR APP'));

		control()->registry()->set('test', 'profile') = $model->get();

		$app = control()->registry()->get('test', 'app');
		$profile = control()->registry()->get('test', 'profile');

		$model = control()
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
		$app = control()->registry()->get('test', 'app');

		$profile = control()
			->model('app')
			->getProfileByToken($app['app_token']);

		$this->assertEquals('TEST FOR APP', $profile['profile_name']);
    }

    public function testApprovePermissions() 
    {	
    	$app = control()->registry()->get('test', 'app');
    	$profile = control()->registry()->get('test', 'profile');

		$yes = control()
			->model('app')
			->permissions(
				$app['app_id'], 
				$profile['profile_id']);
		
		$this->assertTrue($yes);

		$yes = control()
			->model('app')
			->permissions($app['app_id'], 222);

		$this->assertFalse($yes);
    }

    public function testUnlinkAndRemoveProfile() 
    {
    	$app = control()->registry()->get('test', 'app');
    	$profile = control()->registry()->get('test', 'profile');

		$model = control()
			->model('app')
			->unlinkProfile(
				$app['app_id'],
				$profile['profile_id']));
		
		$this->assertEquals(
			$app['app_id'],
			$model['app_profile_app']);

		$this->assertEquals(
			$profile['profile_id'],
			$model['app_profile_profile']);
    }

}