<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

class ApiModelFileRemoveTest extends PHPUnit_Framework_TestCase
{
    public function testRemoveFile() 
	{
        $file = eve()->registry()->get('test', 'file');

        $row = eve()
        	->model('file')
        	->remove()
        	->process(array( 
				'file_id' => $file['file_id']));

		$this->assertEquals(0, $row['file_active']);
    }
}