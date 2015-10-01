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
 * App Model Update
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
class Update extends Base
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
        
        // app_id - required
        if(!isset($data['app_id'])
        || !$this('validation', $data['app_id'])->isType('int', true)) {
            $errors['app_id'] = self::INVALID_REQUIRED;
        }
        
        // app_name - required
        if(isset($data['app_name'])
        && empty($data['app_name'])) {
            $errors['app_name'] = self::INVALID_ID;
        }
        
        // app_domain - required
        if(isset($data['app_domain'])
        && empty($data['app_domain'])) {
            $errors['app_domain'] = self::INVALID_ID;
        }
        
        // app_permissions - required
        if(isset($data['app_permissions'])
        && empty($data['app_permissions'])) {
            $errors['app_permissions'] = self::INVALID_ID;
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
        $updated = date('Y-m-d H:i:s');
        
        //SET WHAT WE KNOW
        $model = eve()
            ->database()
            ->model()
            
            // app_id
            ->setAppId($data['app_id'])
            
            // app_updated
            ->setAppUpdated($updated);
        
        //REQUIRED
        

        // app_name
        if(isset($data['app_name'])) {
            $model->setAppName($data['app_name']);
        }

        // app_domain
        if(isset($data['app_domain'])) {
            $model->setAppDomain($data['app_domain']);
        }

        // app_permissions
        if(isset($data['app_permissions'])) {
            $model->setAppPermissions($data['app_permissions']);
        }
        
        //OPTIONAL
        
        // app_website
        if(isset($data['app_website'])) {
            $model->setAppWebsite($data['app_website']);
        }

        // app_token
        if(isset($data['app_token'])) {
            $model->setAppToken($data['app_token']);
        }

        // app_secret
        if(isset($data['app_secret'])) {
            $model->setAppSecret($data['app_secret']);
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
        
        eve()->trigger('app-update', $model);
        
        return $model;
    }
}