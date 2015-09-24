<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

class ApiModelProfileIndexTest extends PHPUnit_Framework_TestCase
{
    public function tesCreateAndLinkFile() 
	{
     	$model = eve()
     		->model('file')
     		->create()
     		->process(array(
				'file_link' => 'http://example.com/sample.gif'));
		
		eve()->registry()->set('test', 'file', $model->get());

		$file = eve()->registry()->get('test', 'file');
		$profile = eve()->registry()->get('test', 'profile');

		$model = eve()
			->model('profile')
			->linkFile($profile['profile_id'], $file['file_id']);

		$this->assertEquals(
			$profile['profile_id'], 
			$model['profile_file_profile']);

		$this->assertEquals(
			$file['file_id'], 
			$model['profile_file_file']);
    }
	
    public function testUnlinkAndRemoveFile() 
	{
		$file = eve()->registry()->get('test', 'file');
		$profile = eve()->registry()->get('test', 'profile');
		
        $model = eve()
        	->model('profile')
        	->unlinkFile((int)$profile['profile_id'], (int) $file['file_id']);
		
		$this->assertEquals(
			$profile['profile_id'], 
			$model['profile_file_profile']);
		
		$this->assertEquals(
			$file['file_id'], 
			$model['profile_file_file']);
    }

    public function testUnlinkAllFiles() 
    {
		$profile = eve()->registry()->get('test', 'profile');

    	$model = eve()
    		->model('profile')
    		->unlinkAllFiles(
				$profile['profile_id'], []);

    	$this->assertTrue(is_array($model));
    }
}