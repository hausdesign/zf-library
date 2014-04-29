<?php
class HausDesign_Application_Resource_Multidb extends Zend_Application_Resource_Multidb
{
    public function init()
    {
        $return = parent::init();

        foreach ($this->_dbs as $databaseName => $database) {
        	Zend_Registry::set($databaseName, $database);
        }

        return $return;
    }
}