<?php //-->
/*
 * A Custom Library
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace OL\Model\Profile;

use Eve\Framework\Model\Base;
use Eve\Framework\Model\Argument;
use Eve\Framework\Model\Exception;

/**
 * Profile Model Factory
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
     * @return OL\Model\Profile\Create
     */
    public function create()
    {
        return Create::i();
    }
    
    /**
     * Factory for detail
     *
     * @return OL\Model\Profile\Detail
     */
    public function detail()
    {
        return Detail::i();
    }
    
    /**
     * Factory for search
     *
     * @return OL\Model\Profile\Search
     */
    public function search()
    {
        return Search::i();
    }
    
    /**
     * Factory for set
     *
     * @return OL\Model\Profile\Set
     */
    public function set()
    {
        return Set::i();
    }
    
    /**
     * Factory for remove
     *
     * @return OL\Model\Profile\Remove
     */
    public function remove()
    {
        return Remove::i();
    }
    
    /**
     * Factory for restore
     *
     * @return OL\Model\Profile\Restore
     */
    public function restore()
    {
        return Restore::i();
    }
    
    /**
     * Factory for update
     *
     * @return OL\Model\Profile\Update
     */
    public function update()
    {
        return Update::i();
    }
}
