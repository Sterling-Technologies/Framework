<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace OL\Model\Auth;

use Eve\Framework\Model\Base;
use Eve\Framework\Model\Argument;
use Eve\Framework\Model\Exception;

/**
 * Model Factory
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
     * @return this
     */
    public function create()
    {
        return Create::i();
    }
    
    /**
     * Checks to see if slug exists
     *
     * @param string slug
     * @param bool
     */
    public function exists($slug) 
    {
        //argument test
        Argument::i()->test(1, 'string');
            
        $total = eve()
            ->database()
            ->search('auth')
            ->filterByAuthSlug($slug)
            ->getTotal();
            
        return $total > 0;
    }
    
    /**
     * Link auth to Profile
     *
     * @param int auth id
     * @param int profile id
     * @return Eden\Mysql\Model
     */
    public function linkProfile($authId, $profileId) 
    {
        //argument test
        Argument::i()->test(1, 'int')->test(2, 'int');
        
        $model = eve()
            ->database()
            ->model()
            ->setAuthProfileProfile($profileId)
            ->setAuthProfileAuth($authId)
            ->insert('auth_profile');
        
        eve()->trigger('auth-link-profile', $model);
        
        return $model;
    }
    
    /**
     * Factory for remove
     *
     * @return OL\Model\Auth\Remove
     */
    public function remove()
    {
        return Remove::i();
    }
    
    /**
     * Factory for restore
     *
     * @return OL\Model\Auth\Restore
     */
    public function restore()
    {
        return Restore::i();
    }
    
    /**
     * Unlink auth to Profile
     *
     * @param int auth id
     * @param int profile id
     * @return Eden\Mysql\Model
     */
    public function unlinkProfile($authId, $profileId) 
    {
        //argument test
        Argument::i()->test(1, 'int')->test(2, 'int');
            
        $model = eve()
            ->database()
            ->model()
            ->setAuthProfileProfile($profileId)
            ->setAuthProfileAuth($authId)
            ->remove('auth_profile');
        
        eve()->trigger('auth-unlink-profile', $model);
        
        return $model;
    }
    
    /**
     * Factory for update
     *
     * @return OL\Model\Auth\Update
     */
    public function update()
    {
        return Update::i();
    }
}
