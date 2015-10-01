<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace OL\App\Back\Action\App;

use Eve\Framework\Action\Html;

/**
 * App HTML Action Restore
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
class Restore extends Html 
{
    const FAIL_406 = 'No ID Provided';
    const FAIL_401 = 'You do not have permissions to restore';
    const FAIL_404 = 'App does not exist';
    const SUCCESS_200 = 'App successfully restored!';

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
        $data['app_id'] = $this->request->get('variables', 0);
        
        //was it not included in the url ?
        if(!$data['app_id'] 
        && isset($_SESSION['me']['app_id'])) {
            //get it from the session
            $data['app_id'] = $_SESSION['me']['app_id'];
        }
        
        //it's going to fail if we don't have the app_id
        if(!$data['app_id']) {
            //we might as we an fail it now
            return $this->fail(
                self::FAIL_404, 
                '/control/app/search');
        }
        
        //if no profile_id
        if(!isset($_SESSION['me']['profile_id'])) {
            //permission check failed
            return $this->fail(
                self::FAIL_401,
                '/control/app/search');
        }
        
        $data['profile_id'] = $_SESSION['me']['profile_id'];
        
        //-----------------------//
        // 2. Validate
        //check fields
        $errors = eve()
            ->model('app')
            ->update()
            ->errors($data);
        
        //if there are errors
        if(!empty($errors)) {
            return $this->fail(
                self::FAIL_406, 
                '/control/app/search');
        }
        
        //check permissions
        $yes = eve()
            ->model('app')
            ->permissions(
                $data['app_id'], 
                $data['profile_id']);

        //if not permitted, fail
        if(!$yes) {
            return $this->fail(
                self::FAIL_401,
                '/control/app/search');
        }
                
        //-----------------------//
        // 3. Process
        try {
            $results = eve()
                ->job('app-restore')
                ->setData($data)
                ->run();
        } catch(\Exception $e) {
            return $this->fail(
                $e->getMessage(),
                '/control/app/search');
        }
        
        //NOTE: do something with results here
        
        return $this->success(
            self::SUCCESS_200, 
            '/control/app/search');
    }
}