<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Api\Model\App;

use Eve\Framework\Model\Base;
use Eve\Framework\Model\Argument;
use Eve\Framework\Model\Exception;

/**
 * App Model Create
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
 */
class Create extends Base
{
    /**
     * Returns errors if any
     *
     * @param array submitted data
     * @param array existing errors
     * @return array error
     */
    public function errors(array $data = array(), array $errors = array()) 
    {
        //prepare
        $data = $this->prepare($data);
        
        //REQUIRED

        // app_name - required
        if(!isset($data['app_name']) || empty($data['app_name'])) {
            $errors['app_name'] = self::INVALID_REQUIRED;
        }
        

        // app_domain - required
        if(!isset($data['app_domain']) || empty($data['app_domain'])) {
            $errors['app_domain'] = self::INVALID_REQUIRED;
        }
        

        // app_permissions - required
        if(!isset($data['app_permissions']) || empty($data['app_permissions'])) {
            $errors['app_permissions'] = self::INVALID_REQUIRED;
        }
        
        //OPTIONAL
        
        // app_website - url
        if(isset($data['app_website']) 
            && !$this('validation', $data['app_website'])->isType('url')
        ) {
            $errors['app_website'] = self::INVALID_URL;
        }
        
        // app_flag - small
        if(isset($data['app_flag']) 
        && !$this('validate', $data['app_flag'])->isType('small', true)) {
            $errors['app_flag'] = self::INVALID_SMALL;
        }
        
        return $errors;
    }
    
    /**
     * Processes the form
     *
     * @param array data
     * @return mixed
     */
    public function process(array $data = array()) 
    {
        //prevent uncatchable error
        if(count($this->errors($data))) {
            throw new Exception(self::FAIL_406);
        }
        
        //prepare
        $data = $this->prepare($data);
        
        //generate stuff
        $created = date('Y-m-d H:i:s');
        $updated = date('Y-m-d H:i:s');
        
        //SET WHAT WE KNOW
        $model = eve()
            ->database()
            ->model()

            // app_name - required
            ->setAppName($data['app_name'])

            // app_domain - required
            ->setAppDomain($data['app_domain'])

            // app_permissions - required
            ->setAppPermissions($data['app_permissions'])
            
            // app_token - required
            ->setAppToken(md5(uniqid()))
            
            // app_secret - required
            ->setAppSecret(md5(uniqid()))
            
            // app_created
            ->setAppCreated($created)
            
            // app_updated
            ->setAppUpdated($updated);
        
        //OPTIONAL
        
        // app_website
        if(isset($data['app_website'])) {
            $model->setAppWebsite($data['app_website']);
        }


        
        // app_type
        if(isset($data['app_type'])) {
            $model->setAppType($data['app_type']);
        }
        
        // app_flag
        if(isset($data['app_flag'])) {
            $model->setAppFlag($data['app_flag']);
        }
        
        //what's left ?
        $model->save('app');
        
        eve()->trigger('app-create', $model);
        
        return $model;
    }
}