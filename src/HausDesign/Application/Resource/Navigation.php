<?php
class HausDesign_Application_Resource_Navigation extends Zend_Application_Resource_Navigation
{
    public function init()
    {
        $options = $this->getOptions();
        switch ($options['type']) {
            case 'xml':
				// FIX FOR IIS CACHE FOLDER START
				//$config = new Zend_Config_Xml($options['file'], 'nav');
				$xml = file_get_contents($options['file']);
                $config = new Zend_Config_Xml($xml, 'nav');
                $this->_container = new Zend_Navigation($config);
                break;
        }

        parent::init();
    }
}