<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Api\Action\Profile;

use Eve\Framework\Action\Html;

/**
 * Profile HTML Action Update
 *
 * GUIDE:
 * -- eve() - The current server controller
 *    use this to access the rest of the framework
 *
 *    -- eve()->database() - Returns the current database
 *
 *    -- eve()->model('noun') - Returns the given model factory
 *
 *    -- eve()->job('noun-action') - Returns a job following noun/action
 *
 *    -- eve()->settings('foo') - Returns a settings data originating
 *    from the settings path. ie. settings/foo.php
 *
 *    -- eve()->registry() - Returns Eden\Registry\Index used globally
 *
 * -- $this->request - The Request Object using Eden\Registry\Index
 *
 *    -- $this->request->get('post') - $_POST data
 *       You are free to use the $_POST variable if you like
 *
 *    -- $this->request->get('get') - $_GET data
 *       You are free to use the $_GET variable if you like
 *
 *    -- $this->request->get('server') - $_SERVER data
 *       You are free to use the $_SERVER variable if you like
 *
 *    -- $this->request->get('body') - raw body for 
 *       POST requests that provide JSON data for example
 *       instead of the default x-form-data
 *
 *    -- $this->request->get('method') - GET, POST, PUT or DELETE
 *
 * -- $this->response - The Response Object using Eden\Registry\Index
 *
 *    -- $this->response->set('body', 'Foo') - Sets the response body.
 *       Alternative for returning a string in render()
 *
 *    -- $this->response->set('headers', 'Foo', 'Bar') - Sets a 
 *       header item to 'Foo: Bar' given key/value
 *
 *    -- $this->response->set('headers', 'Foo', '') - Sets a 
 *       header item to 'Foo' given that no value is present
 *       QUIRK: $this->response->set('headers', 'Foo') will erase
 *       all existing headers
 */
class Update extends Html 
{
    const FAIL_406 = 'There are some errors on the form.';
    const FAIL_401 = 'You do not have permissions to update.';
    const FAIL_404 = 'Profile does not exist';
    const SUCCESS_200 = 'Profile successfully updated!';

    protected $title = 'Update Profile';
    
    /**
     * Main action call
     *
     * @return string|null|void
     */
    public function render() 
    {
        //-----------------------//
        // 1. Get Data
        $data = array();
        
        //get id from the url
        $data['profile_id'] = $this->request->get('variables', 0);
        
        //was it not included in the url ?
        if(!$data['profile_id'] 
        && isset($_SESSION['me']['profile_id'])) {
            //get it from the session
            $data['profile_id'] = $_SESSION['me']['profile_id'];
        }
        
        //it's going to fail if we don't have the profile_id
        if(!$data['profile_id']) {
            //we might as we an fail it now
            return $this->fail(
                self::FAIL_404,
                '/app/search');
        }
        
        //-----------------------//
        // 2. Validate
        //is it me ?
		if($_SESSION['me']['profile_id'] !== $data['profile_id']) {
			return $this->fail(
				self::FAIL_401,
                '/app/search');
		}
		
		//does it exist?
        $row = eve()
            ->model('profile')
            ->detail()
            ->process($data)
            ->getRow();
        
        if(!$row) {
            return $this->fail(
                self::FAIL_404,
                '/app/search');
        }
        
        //-----------------------//
        // 3. Process
        //if it's a post
        if(!empty($_POST)) {
            return $this->check($data);
        }
        
        $this->body['item'] = $row;
        
        //NOTE: add anything extra to body here
        
        //success
        return $this->success();
    }

    /**
     * When the form is submitted
     *
     * @param array
     * @return void
     */
    protected function check($data) 
    {
        //-----------------------//
        // 1. Get Data
        $data = array('item' => array_merge($data, $this->request->get('post')));
        
        if(isset($data['item']['profile_birth']) 
            && !strtotime($data['item']['profile_birth'])
        ) {
            $data['item']['profile_birth'] = null;
        }
        //-----------------------//
        //2. Validate
        $errors = eve()
            ->model('profile')
            ->update()
            ->errors($data['item']);
        
        //if there are errors
        if(!empty($errors)) {
            return $this->fail(
                self::FAIL_406, 
                $errors, 
                $data['item']);
        }
        
        //-----------------------//
        //3. Process
        try {
            $results = eve()
                ->job('profile-update')
                ->setData($data['item'])
                ->run();
        } catch(\Exception $e) {
            return $this->fail(
                $e->getMessage(), 
                array(), 
                $data['item']);
        }
        
        //NOTE: do something with results here
        
        return $this->success(
            self::SUCCESS_200, 
            '/app/search');
    }
}
