<?php //-->
/*
 * This file is part of the Type package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace test\Model\Profile;

class Index extends PHPUnit_Framework_TestCase
{
    public function tesCreateAndLinkFile() 
	{
     	$model = control()
     		->model('file')
     		->create()
     		->process(array(
				'file_link' => 'http://example.com/sample.gif'));
		
		control()->registry()->set('test', 'file', $model->get());

		$file = control()->registry()->get('test', 'file');
		$profile = control()->registry()->get('test', 'profile');

		$model = control()
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
		$file = control()->registry()->get('test', 'file');
		$profile = control()->registry()->get('test', 'profile');

        $model = control()
        	->model('profile')
        	->unlinkFile($profile['profile_id'], $file['file_id']);
		
		$this->assertEquals(
			$profile['profile_id'], 
			$model['profile_file_profile']);
		
		$this->assertEquals(
			$file['file_id'], 
			$model['profile_file_file']);
    }

    public function testUnlinkAllFiles() 
    {
		$profile = control()->registry()->get('test', 'profile');

    	$model = control()
    		->model('profile')
    		->unlinkAllFiles(
				$profile['profile_id'], []);

    	$this->assertTrue(is_array($model));
    }
}