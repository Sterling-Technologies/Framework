<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

class ApiModelFileSearchTest extends PHPUnit_Framework_TestCase
{
    public function testGetFiles() 
	{
     	$rows = eve()
			->model('file')
			->search()
			->process();
		
		foreach ($rows as $row) {
			$this->assertEquals(1, $row['file_active']);
		}
    }
}