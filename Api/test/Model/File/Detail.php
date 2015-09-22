<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\File;

class Detail extends PHPUnit_Framework_TestCase
{
    public function testGetFile() 
	{
		$file = control()->registry()->get('test', 'file');
     
        $row = control()
			->model('file')
			->detail()
			->process(array('file_id' => $file['file_id']));
		
		$this->assertEquals('http://example.com/sample.jpg', $row['file_link']);
    }
}