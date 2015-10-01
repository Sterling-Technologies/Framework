<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */
namespace OL\App\Dialog\Action;

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
class Login extends Html
{
    const FAIL_404 = 'User or Password is incorrect';
    const FAIL_406 = 'There are some errors on the form.';
    
    protected $title = 'Log In';
    protected $layout = '_blank';
    protected $template = 'login';
    
    public function render() 
    {
        //there should be a client_id, redirect_uri
        //client_id is already checked in the router
        //state is optional
        if(!isset($_GET['redirect_uri'])) {
            $this->template = 'invalid';
            return $this->success();
        }
        
        //okay it is permitted
        //if there's a session
        if(isset($_SESSION['me'])) {
            //no need to login
            $query = $this->request->get('query');
            eve()->redirect('/dialog/request?' . $query);
        }
        
        //if it's a post
        if(!empty($_POST)) {
            return $this->check();
        }
        
        $this->data['query'] = $_SERVER['QUERY_STRING'];
        
        //Just load the page
        return $this->success();
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
        //-----------------------//
        // 1. Get Data
        $data = array();
        
        $data['item'] = $this->request->get('post');

        //-----------------------//
        // 2. Validate
        $errors = eve()
            ->model('session')
            ->login()
            ->errors($data['item']);

        if(!empty($errors)) {
            return $this->fail(
                self::FAIL_406, 
                $errors, 
                $data['item']);
        }
        
        //-----------------------//
        // 3. Process
        $row = eve()
            ->model('session')
            ->login()
            ->process($data['item']);

        if(empty($row)) {
            return $this->fail(
                self::FAIL_404,
                array(),
                $data['item']);
        }

        unset($row['auth_password']);
        
        //assign a new session
        $_SESSION['me'] = $row;

        //pass the request query
        $query = $this->request->get('query');
        eve()->redirect('/dialog/request?' . $query);
    }
}
