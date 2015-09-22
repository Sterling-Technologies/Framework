<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\Auth;

class Index extends PHPUnit_Framework_TestCase
{
    public function testExist() 
	{
        $auth = control()->registry()->get('test', 'auth');

		$total = control()
			->model('auth')
			->exists($auth['auth_slug']);

		$this->assertEquals(1, $total);
    }
	
    public function testCreateAndLinkProfile() 
	{
        $model = control()
        	->model('profile')
        	->create()
        	->process(array(
				'profile_name' => 'TEST FOR AUTH'));
		
		control()->registry()->set('test', 'profile', $model->get());

        $profile = control()->registry()->get('test', 'profile');
        $auth = control()->registry()->get('test', 'auth');

		$model = control()->model('auth')
			->linkProfile(
				$auth['auth_id'], 
				$profile['profile_id']));

		$this->assertEquals(
			$auth['auth_id'],
			$model['auth_profile_auth']);

		$this->assertEquals(
			$profile['profile_id'],
			$model['auth_profile_profile']);
    }

    public function testCreateAndLinkProfile() 
    {
    	$profile = control()->registry()->get('test', 'profile');
        $auth = control()->registry()->get('test', 'auth');

    	$model = model('auth')
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