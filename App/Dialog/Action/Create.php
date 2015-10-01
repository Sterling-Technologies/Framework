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
class Create extends Html
{
    const FAIL_406 = 'There are some errors on the form.';
    const SUCCESS_200 = 'You can now Log In!';

    protected $title = 'Sign Up';
    protected $layout = '_blank';
    protected $template = 'create';

    public function render() 
    {
        //if it's a post
        if(!empty($_POST)) {
            return $this->check();
        }
        
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
        $data['item'] = $this->request->get('post');
        
        $data['item']['auth_slug'] = $data['item']['profile_email'];
        $data['item']['auth_permissions'] = implode(',', eve()->settings('scope'));
        
        $data['item']['profile_image'] = 
            $_SERVER['HTTP_HOST'] . '/avatar/avatar-' 
            . ((floor(rand() * 1000) % 11) + 1) . '.png';
        
        //-----------------------//
        // 2. Validate
        $errors = eve()
            ->model('auth')
            ->create()
            ->errors($data['item']);
        
        $errors = eve()
            ->model('profile')
            ->create()
            ->errors($data['item'], $errors);
        
        //if there are errors
        if(!empty($errors)) {
            return $this->fail(
                self::FAIL_406, 
                $errors, 
                $data['item']);
        }
            
        //-----------------------//
        // 3. Process
        try {
            $results = eve()
                ->job('auth-create')
                ->setData($data['item'])
                ->run();
        } catch(\Exception $e) {
            return $this->fail(
                $e->getMessage(),
                array(),
                $data['item']
            );
        }
        
        //success
        $query = $this->request->get('query');
        
        $_SESSION['message'] = self::SUCCESS_200;
        $_SESSION['type'] = 'success';

        eve()->redirect('/dialog/login?' . $query);
    }
}
