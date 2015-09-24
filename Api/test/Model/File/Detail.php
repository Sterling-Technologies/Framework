<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

class ApiModelFileDetailTest extends PHPUnit_Framework_TestCase
{
    public function testGetFile() 
	{
		$file = eve()->registry()->get('test', 'file');
     
        $row = eve()
			->model('file')
			->detail()
			->process(array('file_id' => $file['file_id']))
			->getRow();
			
		$this->assertEquals('http://example.com/sample.jpg', $row['file_link']);
    }
}