<?php //-->
/*
 * This file is part of the Openovate Labs Inc. framework library
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace Api\Action\App;

use Eve\Framework\Action\Json;
use Eve\Framework\Action\Html;

/**
 * The base class for any class that defines a view.
 * A view controls how templates are loaded as well as 
 * being the final point where data manipulation can occur.
 *
 * @vendor Openovate
 * @package Framework
 */
class Create extends Html 
{
	const FAIL_406 = 'There are some errors on the form.';
    const SUCCESS_200 = 'App successfully created!';
	
	protected $title = 'Create an App';

	public function render() 
	{	
		//if it's a post
		if(!empty($_POST)) {
			return $this->check();
		}
		
		//Just load the page
		return $this->success();
	}

	/**
	 * When the form is submitted
	 *
	 * @return void
	 */
	protected function check()
	{
		//-----------------------//
        // 1. Get Data
		$data = array();
		
		$data['item'] = $this->request->get('post');

		$data['profile_id'] = $_SESSION['me']['profile_id'];
		
		//add permissions
		if(is_array($data['item']['app_permissions'])) {
			$data['item']['app_permissions'] = implode(',', $data['item']['app_permissions']);
			
			//reset the roles
			$data['roles'] = explode(',', $this->getRoles($data['item']['app_permissions']));
		}

		//validate

		//get errors
		$errors = eve()
			->model('app')
			->create()
			->errors($data['item']);
		
		//if there are errors
		if(!empty($errors)) {
			return $this->fail(
				self::FAIL_406, 
				$errors, 
				$data['item']);
		}

		//process
		$results = eve()
			->job('app-create')
			->setData($data['item'])
			->run();

		//success
		$this->success(self::SUCCESS_200, '/app/list');
	}

	/**
	 * Sets up the roles object
     *
     * @param array
     * @return object
     */
	protected function getRoles(array $permissions = array())
	{
        $roles = eve()->config('roles');

        //try not to use the global roles
        $label = $role = $max = 0;
        $localRoles = array();
        //reset all the roles
        foreach($roles as $label) {
            $localRoles[$label] = array();
			
			foreach($roles[$label] as $role) {
				$localRoles[$label][] = array(
					'name' => $role,
					'title' => $roles[$label][$role]['title'],
					'description' => $roles[$label][$role]['description'],
					'checked' => array_key_exists($role, $permissions) == 1 ? false : true
				);
			}
        }
    	
    	return $localRoles;
    }
}