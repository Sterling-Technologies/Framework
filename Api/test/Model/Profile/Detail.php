<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

class ApiModelProfileDetailTest extends PHPUnit_Framework_TestCase
{
    public function testGetProfile() 
	{
		$profile = eve()->registry()->get('test', 'profile');
     
        $row = eve()
			->model('profile')
			->detail()
			->process(array('profile_id' => $profile['profile_id']));
		echo $row;
		// $this->assertNull($row);
    }
}