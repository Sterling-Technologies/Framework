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
 * App HTML Action Search
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
class Search extends Html 
{
    const FAIL_401 = 'You do not have permissions to search';
    const FAIL_404 = 'No IDs or action found.';
    const SUCCESS_200 = 'Apps successfully %s';
    
    protected $title = 'Search ';
    protected $range = 50;
    
    /**
     * Main action call
     *
     * @return string|null|void
     */
    public function render() 
    {
        //-----------------------//
        // 1. Get Data
        $data = $this->request->get('get');
        
        //-----------------------//
        // 2. Validate
        //if no profile_id
        if(!isset($_SESSION['me']['profile_id'])) {
            //permission check failed
            return $this->fail(
                self::FAIL_401,
                '/control/login');
        }
        
        //-----------------------//
        // 3. Process
        if(!empty($_POST)) {
            return $this->check();
        }
        
        $data['range'] = $this->range;
        
        $mode = 'active';
        if(isset($data['mode'])) {
           $mode = $data['mode'];
        }
        
        switch($mode) {
            case 'active':
                $data['filter']['app_active'] = 1;
                break;
            case 'trash':
                $data['filter']['app_active'] = 0;
                break;
        }
        
        $search = eve()
            ->model('app')
            ->search()
            ->process($data);
        
        //join profile_id
        $search
            ->innerJoinOn(
                'app_profile', 
                'app_profile_app = app_id')
            ->filterByAppProfileProfile($_SESSION['me']['profile_id']);
        
        //get rows  
        $rows = $search->getRows();
        //get total
        $total = $search->getTotal();
        
        foreach($rows as $i => $row) {
            
            $rows[$i]['app_updated'] = date('M d', strtotime($row['app_updated']));
        }
        
        $this->body['rows'] = $rows;
        $this->body['total'] = $total;
        $this->body['range'] = $this->range;
        $this->body['mode'] = $mode;
        $this->body['keyword'] = null;
        
        if(isset($_GET['keyword'])) {
            $this->body['keyword'] = $_GET['keyword'];
        }
        
        //NOTE: add anything extra to body here
        
        //success
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
        $data = $this->request->get('post');
        
        //-----------------------//
        //2. Validate
        if(!isset($data['action']) 
            || !isset($data['id']) 
            || !is_array($data['id'])
        ) {
            return $this->fail(
                self::FAIL_404,
                '/control/app/search');
        }
        
        //-----------------------//
        //3. Process
        foreach($data['id'] as $id) {
               $item = array('app_id' => $id);
            
            try {
                eve()
                    ->job('app-'.$data['action'])
                    ->setData($item)
                    ->run();
            } catch(\Exception $e) {
                return $this->fail(
                    $e->getMessage,
                    '/control/app/search');
            }
        }
        
        return $this->success(
            sprintf(self::SUCCESS_200, $data['action'].'d'), 
            '/control/app/search');
    }
}
