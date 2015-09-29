<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiJobProfileUpdateTest extends PHPUnit_Framework_TestCase
{
    public function testRun()
	{
		$thrown = false;
		try {
			eve()
				->job('profile-update')
				->run();
		} catch(Exception $e) {
			$this->assertInstanceOf('Eve\\Framework\\Job\\Exception', $e);
			$thrown = true;
		}
		
		$this->assertTrue($thrown);
		
		$profile = eve()->registry()->get('test', 'profile');
		
		$results = eve()
			->job('profile-update')
			->setData(array(
				'profile_id'	=> $profile['profile_id'],
				'profile_name'	=> 'Test Job Profile Update'
			))
			->run();
		
		$this->assertTrue(is_numeric($results['profile']['profile_id']));
		
		$this->assertEquals('Test Job Profile Update', $results['profile']['profile_name']);
	}
}