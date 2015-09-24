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
class Update extends Page 
{
	const FAIL_NOT_EXISTS = 'App does not exist';
	const FAIL_PERMISSIONS = 'You do not have permissions to update.';
	const FAIL_VALIDATION = 'There are some errors on the form.';
	const SUCCESS = 'App successfully updated!';

	protected $title = 'Updating App';

	public function render() 
	{
		$this->data['roles'] = $this->getRoles();
		
		//if it's a post
		if(!empty($_POST)) {
			return $this->check();
		}
	
		//get item
		$item = array(
			'app_id' => (int) $this->data['params']['id'],
			'profile_id' => $_SESSION['me']['profile_id']);

		//check permissions
		$yes = eve()
			->model('app')
			->permissions(
				$item['app_id'], 
				$item['profile_id']);

		//if not permitted, fail
		if(empty($yes)) {
			return $this->fail(self::FAIL_PERMISSIONS, '/app/list');
		}
		
		$row = eve()
			->model('app')
			->detail()
			->process($item)
			->getRow();

		//we are formatting this for handlebars
		$this->data['roles'] = $this->getRoles(explode(',', $row['app_permissions']));
		
		$this->data['item'] = $row;
		
		$this->success();
	}

	/* Methods
	-------------------------------*/
	/**
	 * When the form is submitted
	 *
	 * @return void
	 */
	protected function check() 
	{
		//get the item
		$item = $this->data['item'];
		//add app

		$item['app_id'] = (int) $this->data['params']['id'];
			
		//set permissions		
		if(is_array($this->data['item']['app_permissions'])) {
			$this->data['item']['app_permissions'] = implode(',', $this->data['item']['app_permissions']);
		}
		
		$this->data['roles'] = $this->getRoles(explode(',', $this->data['item']['app_permissions']));
		

		//validate
		$errors = eve()
			->model('app')
			->update()
			->errors($item);
		
		//if there are errors, fail
		if(!empty($errors)) {
			return $this->fail(self::FAIL_VALIDATION, $errors, $item);
		}
			
		eve()->job('app')
			->update(array(
				'data' => array(
					'item' => $item))
			);
		
		$this->success(self::SUCCESS, '/app/list');
	}

	/**
	 * Sets up the roles object
	 *
	 * @param array
	 * @return object
	 */
	protected function getRoles(array $permissions = array())
		$permissions = $permissions || [];

        $roles = eve()->config('roles');

        //try not to use the global roles
        $label = $role = $max = 0;
        $localRoles = {};
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
