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
class Logout extends Html
{
    public function render() 
    {
        //there should be a client_id, redirect_uri
        //client_id is already checked in the router
        //state is optional
        if(!isset($_GET['redirect_uri'])) {
            $this->template = 'invalid';
            return $this->success();
        }
        
        if(!isset($_SESSION['me'])) {
            return $this-redirect(array('error' => 'user_invalid'));
        }
        
        $data = array('auth_id' => $_SESSION['me']['auth_id']);
        
        if(isset($_GET['session_token'])) {
            $data['session_token'] = $_GET['session_token'];
        }
        
        $errors = eve()
            ->model('session')
            ->logout()
            ->errors($data);
        
        if(isset($errors['auth_id'])) {
            return $this->redirect(array('error' => 'user_invalid'));
        }
        
        eve()
            ->model('session')
            ->logout()
            ->process($data);

        unset($_SESSION['me']);
        
        $this->redirect(array( 'success' => 1 ));    
    }

    /**
     * Creates a redirect url
     *
     * @param object extra parameters
     * @return string
     */
    protected function redirect(array $query = array()) 
    {
        $url = $_GET['redirect_uri'];
        
        if(isset($_GET['state'])) {
            $query['state'] = $_GET['state'];
        }
        
        $query = http_build_query($query);
        
        if(empty($query)) {
            eve()->redirect($url);
        }
        
        $separator = '?';
        if(strpos($url, '?') !== false) {
            $separator = '&';
        }
        
        eve()->redirect($url . $separator . $query);
    }
}
