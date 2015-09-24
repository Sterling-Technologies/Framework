<?php //-->
/*
 * This file is part of the Openovate Labs Inc. framework library
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace Api\Action\App;

use Api\Action;
use Api\Page;

/**
 * The base class for any class that defines a view.
 * A view controls how templates are loaded as well as 
 * being the final point where data manipulation can occur.
 *
 * @vendor Openovate
 * @package Framework
 */
class Create extends Page 
{
	const FAIL_VALIDATION = 'There are some errors on the form.';
    const SUCCESS = 'App successfully created!';
	
	protected $title = 'Create an App';

	public function render() 
	{
		$this->data['logo'] = true;
		
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
		$item = $this->data['item'];

		$item['profile_id'] = $_SESSION['me']['profile_id'];
		
		//add permissions
		if(is_array($item['app_permissions']) {
			$item['app_permissions'] = implode(',', $item['app_permissions']);
			
			//reset the roles
			$this->data['roles'] = explode(',' $this->getRoles($item['app_permissions']));
		}

		//validate

		//get errors
		$errors = eve()
			->model('app')
			->create()
			->errors($item);
		
		//if there are errors
		if(!empty($errors)) {
			return $this->fail(self::FAIL_VALIDATION, $errors, $this.item);
		}

		//process
		eve()
			->job('app')
			->create(array(
				'data' => array(
					'item' => $item,
					'profile_id' => $item['profile_id'])
				));

		//success
		$this->success(self::SUCCESS, '/app/list');
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
        for($roles as $label) {
            $localRoles[$label] = [];
                for($roles[$label] as $role) {
                $localRoles[$label][] = array(
                    'name' => $role,
                    'title' => $roles[$label][$role]['title'],
                    'description' => $roles[$label][$role]['description'],
                    'checked' => array_key_exists($role, $permissions) == 1 ? false : true
                });
            }
        }
    	
    	return $localRoles;
    }
}