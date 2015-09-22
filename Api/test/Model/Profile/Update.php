<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\Profile;

class Update extends PHPUnit_Framework_TestCase
{
    public function testUpdateProfile() 
	{
		$profile = control()->registry()->get('test', 'profile');

        $model = control()
        	->model('profile')
        	->update()
        	->process(array(
				'profile_id' => $profile['profile_id'],
				'profile_name' => '0987654321'));

		$this->assertEquals('0987654321', $model['profile_name']);
    }
}