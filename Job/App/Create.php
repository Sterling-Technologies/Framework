<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace OL\Job\App;

use Eve\Framework\Job\Base;
use Eve\Framework\Job\Exception;

/**
 * App Job Create
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
 * -- $this->data - Provides all raw data
 *    originally passed into the job
 */
class Create extends Base 
{
    const FAIL_406 = 'Invalid Data';
    
    /**
     * Executes the job
     *
     * @return void
     */
    public function run() 
    {
        //if no data
        if(empty($this->data)) {
            //there should be a global catch somewhere
            throw new Exception(self::FAIL_406);
        }
        
        //this will be returned at the end
        $results = array();
        
        //NEXT ...
        
        //if there is no app_id provided
        if(!isset($this->data['app_id'])) {
            //create the app
            $results['app'] = eve()
                ->model('app')
                ->create()
                ->process($this->data)
                ->get();
           
           $this->data['app_id'] = $results['app']['app_id'];
        }
                
        //NEXT ...
        
        //if there is a profile_id
        if(isset($this->data['profile_id'])) {
            //link the profile
            eve()
                ->model('app')
                ->linkProfile(
                    $results['app']['app_id'],
                    $this->data['profile_id']);
        }
                
        return $results;
    }
}