<?php
class HausDesign_Application_Resource_Cachemanager extends Zend_Application_Resource_Cachemanager
{
    /**
     * Initialize Cache_Manager
     *
     * @return Zend_Cache_Manager
     */
    public function init()
    {
        $cacheManager = parent::getCacheManager();

        Zend_Registry::set('cache_manager', $cacheManager);

        return $cacheManager;
    }
}