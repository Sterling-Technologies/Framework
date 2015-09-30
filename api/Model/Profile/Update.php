<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Api\Model\Profile;

use Eve\Framework\Model\Base;
use Eve\Framework\Model\Argument;
use Eve\Framework\Model\Exception;

/**
 * Profile Model Update
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
        
        // profile_id - required
        if(!isset($data['profile_id'])
        || !$this('validation', $data['profile_id'])->isType('int', true)) {
            $errors['profile_id'] = self::INVALID_REQUIRED;
        }
        
        // profile_name - required
        if(isset($data['profile_name'])
        && empty($data['profile_name'])) {
            $errors['profile_name'] = self::INVALID_REQUIRED;
        }
        
        //OPTIONAL
        
        // profile_gender - one of
        $choices = array('male', 'female');
        if(isset($data['profile_gender']) 
            && !empty($data['profile_gender'])
            && !in_array($data['profile_gender'], $choices)
        ) {
            $errors['profile_gender'] = sprintf(self::INVALID_ONEOF, implode(',', $choices));
        }
        
        // profile_birth - date
        if(isset($data['profile_birth']) 
            && !empty($data['profile_birth'])
            && !$this('validation', $data['profile_birth'])->isType('date')
        ) {
            $errors['profile_birth'] = self::INVALID_DATE;
        }
        
        // profile_facebook - url
        if(isset($data['profile_facebook']) 
            && !empty($data['profile_facebook'])
            && !$this('validation', $data['profile_facebook'])->isType('url')
        ) {
            $errors['profile_facebook'] = self::INVALID_URL;
        }
        
        // profile_linkedin - url
        if(isset($data['profile_linkedin']) 
            && !empty($data['profile_linkedin'])
            && !$this('validation', $data['profile_linkedin'])->isType('url')
        ) {
            $errors['profile_linkedin'] = self::INVALID_URL;
        }
        
        // profile_twitter - url
        if(isset($data['profile_twitter']) 
            && !empty($data['profile_twitter'])
            && !$this('validation', $data['profile_twitter'])->isType('url')
        ) {
            $errors['profile_twitter'] = self::INVALID_URL;
        }
        
        // profile_google - url
        if(isset($data['profile_google']) 
            && !empty($data['profile_google'])
            && !$this('validation', $data['profile_google'])->isType('url')
        ) {
            $errors['profile_google'] = self::INVALID_URL;
        }
        
        // profile_flag - small
        if(isset($data['profile_flag']) 
            && !empty($data['profile_flag'])
            && !$this('validate', $data['profile_flag'])->isType('small', true)
        ) {
            $errors['profile_flag'] = self::INVALID_SMALL;
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
        
        //upload profile_image
        if(isset($_FILES['profile_image']['tmp_name'])
            && !empty($_FILES['profile_image']['tmp_name'])
        ) {
            $destination = eve()->path('upload');
            
            if(!is_dir($destination)) {
                   mkdir($destination);
            }
            
            $file = '/' . md5(uniqid()) . '-' . $_FILES['profile_image']['name'];
            
            move_uploaded_file($_FILES['profile_image']['tmp_name'], $destination.$file);
            
            $data['profile_image'] = 'http://'.$_SERVER['HTTP_HOST'].'/upload'.$file;
        }
        
        //SET WHAT WE KNOW
        $model = eve()
            ->database()
            ->model()
            
            // profile_id
            ->setProfileId($data['profile_id'])
            
            // profile_updated
            ->setProfileUpdated($updated);
        
        //REQUIRED
        

        // profile_name
        if(isset($data['profile_name'])
            && !empty($data['profile_name'])
        ) {
            $model->setProfileName($data['profile_name']);
        }
        
        //OPTIONAL
        
        // profile_email
        if(isset($data['profile_email'])
            && !empty($data['profile_email'])
        ) {
            $model->setProfileEmail($data['profile_email']);
        }

        // profile_phone
        if(isset($data['profile_phone'])
            && !empty($data['profile_phone'])
        ) {
            $model->setProfilePhone($data['profile_phone']);
        }

        // profile_detail
        if(isset($data['profile_detail'])
            && !empty($data['profile_detail'])
        ) {
            $model->setProfileDetail($data['profile_detail']);
        }

        // profile_image
        if(isset($data['profile_image'])
            && !empty($data['profile_image'])
        ) {
            $model->setProfileImage($data['profile_image']);
        }

        // profile_company
        if(isset($data['profile_company'])
            && !empty($data['profile_company'])
        ) {
            $model->setProfileCompany($data['profile_company']);
        }

        // profile_job
        if(isset($data['profile_job'])
            && !empty($data['profile_job'])
        ) {
            $model->setProfileJob($data['profile_job']);
        }

        // profile_gender
        if(isset($data['profile_gender'])
            && !empty($data['profile_gender'])
        ) {
            $model->setProfileGender($data['profile_gender']);
        }

        // profile_birth
        if(isset($data['profile_birth'])
            && !empty($data['profile_birth'])
        ) {
            $model->setProfileBirth($data['profile_birth']);
        }

        // profile_facebook
        if(isset($data['profile_facebook'])
            && !empty($data['profile_facebook'])
        ) {
            $model->setProfileFacebook($data['profile_facebook']);
        }

        // profile_linkedin
        if(isset($data['profile_linkedin'])
            && !empty($data['profile_linkedin'])
        ) {
            $model->setProfileLinkedin($data['profile_linkedin']);
        }

        // profile_twitter
        if(isset($data['profile_twitter'])
            && !empty($data['profile_twitter'])
        ) {
            $model->setProfileTwitter($data['profile_twitter']);
        }

        // profile_google
        if(isset($data['profile_google'])
            && !empty($data['profile_google'])
        ) {
            $model->setProfileGoogle($data['profile_google']);
        }

        // profile_reference
        if(isset($data['profile_reference'])
            && !empty($data['profile_reference'])
        ) {
            $model->setProfileReference($data['profile_reference']);
        }
        
        // profile_type
        if(isset($data['profile_type'])
            && !empty($data['profile_type'])
        ) {
            $model->setProfileType($data['profile_type']);
        }
        
        // profile_flag
        if(isset($data['profile_flag'])
            && !empty($data['profile_flag'])
        ) {
            $model->setProfileFlag($data['profile_flag']);
        }
        
        //what's left ?
        $model->save('profile');
        
        eve()->trigger('profile-update', $model);
        
        return $model;
    }
}