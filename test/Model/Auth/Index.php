<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
class ApiModelAuthIndexTest extends PHPUnit_Framework_TestCase
{
	public function testCreate()
	{
		$class = eve()->model('auth')->create();
		$this->assertInstanceOf('OL\\Model\\Auth\\Create', $class);
	}
	
	public function testRemove()
	{
		$class = eve()->model('auth')->remove();
		$this->assertInstanceOf('OL\\Model\\Auth\\Remove', $class);
	}
	
	public function testUpdate()
	{
		$class = eve()->model('auth')->update();
		$this->assertInstanceOf('OL\\Model\\Auth\\Update', $class);
	}
	
    public function testExist() 
	{
        $auth = eve()->registry()->get('test', 'auth');

		$total = eve()
			->model('auth')
			->exists($auth['auth_slug']);

		$this->assertEquals(1, $total);
    }
	
    public function testLinkProfile() 
	{
        $auth = eve()->registry()->get('test', 'auth');

		$model = eve()->model('auth')
			->linkProfile(
				$auth['auth_id'], 
				400);

		$this->assertEquals(
			$auth['auth_id'],
			$model['auth_profile_auth']);

		$this->assertEquals(
			400,
			$model['auth_profile_profile']);
    }

    public function testUnlinkProfile() 
    {
    	$auth = eve()->registry()->get('test', 'auth');

    	$model = eve()
    		->model('auth')
    		->unlinkProfile(
    			$auth['auth_id'], 
    			400);
    		
    	$this->assertEquals(
			$auth['auth_id'],
			$model['auth_profile_auth']);

		$this->assertEquals(
			400,
			$model['auth_profile_profile']);	
    }
}