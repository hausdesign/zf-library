<?php
class HausDesign_Application_Resource_Navigation extends Zend_Application_Resource_Navigation
{
    public function init()
    {
        $options = $this->getOptions();
        switch ($options['type']) {
            case 'xml':
                $config = new Zend_Config_Xml($options['file'], 'nav');
                $this->_container = new Zend_Navigation($config);
                break;
        }

        parent::init();
    }
}