<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace OL\Model\App;

use Eve\Framework\Model\Base;
use Eve\Framework\Model\Argument;
use Eve\Framework\Model\Exception;

/**
 * App Model Factory
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
class Index extends Base
{
    /**
     * Factory for create
     *
     * @return OL\Model\App\Create
     */
    public function create()
    {
        return Create::i();
    }
    
    /**
     * Factory for detail
     *
     * @return OL\Model\App\Detail
     */
    public function detail()
    {
        return Detail::i();
    }
    
    /**
     * Get profile by app access token
     * Random function needed...
     *
     * @param string
     * @return array
     */
    public function getProfileByToken($token) 
    {
        //argument test
        Argument::i()->test(1, 'string');
        
        return eve()
            ->database()
            ->search('app')
            ->setColumns(
                'profile.*', 
                'app.*')
            ->innerJoinOn(
                'app_profile', 
                'app_profile_app = app_id')
            ->innerJoinOn(
                'profile', 
                'app_profile_profile = profile_id')
            ->filterByAppToken($token)
            ->getRow();
    }
    
    /**
     * Link app to profile
     *
     * @param int app id
     * @param int profile id
     * @return Eden\Mysql\Model
     */
    public function linkProfile($appId, $profileId) 
    {
        //argument test
        Argument::i()->test(1, 'int')->test(2, 'int');
        
        $model = eve()
            ->database()
            ->model()
            ->setAppProfileApp($appId)
            ->setAppProfileProfile($profileId)
            ->insert('app_profile');
        
        eve()->trigger('app-link-profile', $model);
        
        return $model;
    }
    
    
    /**
     * Check for app permissions
     * 
     * @param int app id
     * @param int profile id
     * @return bool
     */
    public function permissions($appId, $profileId) 
    {
        //argument test
        Argument::i()->test(1, 'int')->test(2, 'int');
        
        $row = eve()
            ->database()
            ->search('app_profile')
            ->filterByAppProfileApp($appId)
            ->filterByAppProfileProfile($profileId)
            ->getRow();
        
        if(!$row) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Factory for search
     *
     * @return OL\Model\App\Search
     */
    public function search()
    {
        return Search::i();
    }
    
    /**
     * Factory for refresh
     *
     * @return OL\Model\App\Refresh
     */
    public function refresh()
    {
        return Refresh::i();
    }
    
    /**
     * Factory for remove
     *
     * @return OL\Model\App\Remove
     */
    public function remove()
    {
        return Remove::i();
    }
    
    /**
     * Factory for restore
     *
     * @return OL\Model\App\Restore
     */
    public function restore()
    {
        return Restore::i();
    }
    
    /**
     * Unlink app to profile
     *
     * @param int app id
     * @param int profile id
     * @return Eden\Mysql\Model
     */
    public function unlinkProfile($appId, $profileId) 
    {
        //argument test
        Argument::i()->test(1, 'int')->test(2, 'int');
        
        $model = eve()
            ->database()
            ->model()
            ->setAppProfileApp($appId)
            ->setAppProfileProfile($profileId)
            ->remove('app_profile');
        
        eve()->trigger('app-unlink-profile', $model);
        
        return $model;
    }
    
    /**
     * Factory for update
     *
     * @return OL\Model\App\Update
     */
    public function update()
    {
        return Update::i();
    }
}
