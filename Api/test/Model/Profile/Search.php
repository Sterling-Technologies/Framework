<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

class ApiModelProfileSeachTest extends PHPUnit_Framework_TestCase
{
    public function testGetActiveApps() 
	{
        $rows = eve()
        	->model('profile')
        	->search()
        	->process()
        	->getRows();
		
		foreach ($rows as $row) {
			$this->assertEquals(1, $row['profile_active']);
		}
    }
}