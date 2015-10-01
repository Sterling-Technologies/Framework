<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace OL\App\Rest\Action;

use Eve\Framework\Action\Json;
use Eve\Framework\Action\Html;

/**
 * Action
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
class Access extends Json
{
    public function render() 
    {
        //-----------------------//
        // 1. Get Data
        $data = array();
        
        $data['item'] = $this->request->get('post');
        $data['item']['client_id'] = $this->request->get('source', 'access_token');
        $data['item']['client_secret'] = $this->request->get('source', 'access_secret');

        //-----------------------//
        // 2. Validate
        $errors = eve()
            ->model('session')
            ->access()
            ->errors($data['item']);
    
        if(isset($errors['code'])) {
            return $this->fail($errors['code']);
        }
        
        //-----------------------//
        // 3. Process
        try {
            $results = eve()
                ->job('session-access')
                ->setData($data['item'])
                ->run();
        } catch(\Exception $e) {
            return $this->fail($e->getMessage());
        }
        
        $this->success($results['session']);    
    }
}
