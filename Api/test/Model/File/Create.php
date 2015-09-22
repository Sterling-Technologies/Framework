<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\File;

class Create extends PHPUnit_Framework_TestCase
{
    public function testValidateFileFields() 
	{
        $errors = control()->model('file')->create()->errors();
		
		$this->assertEquals('Cannot be empty!', $errors['file_data']);
		$this->assertEquals('Cannot be empty!', $errors['file_link']);
    }
	
    public function testCreateFile() 
	{
        $model = control()
        	->model('file')
        	->create()
        	->process(array(
				'file_link' => 'http://example.com/sample.jpg'));

		$this->assertTrue(is_int($model['file_id']));
		control()->registry()->set('test', 'file', $model->get());
    }
}