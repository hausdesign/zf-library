<?php
class HausDesign_Filter_HtmlPurifier implements Zend_Filter_Interface
{
    protected $_config;
    protected $_purifier;

    public function __construct()
    {
        require_once 'HTMLPurifier.auto.php';
    }

    public function filter($value)
    {
        $value = $this->getPurifier()->purify($value);
        return $value;
    }

    public function getConfig()
    {
        if (null === $this->_config) {
            $config = HTMLPurifier_Config::createDefault();
            $config->set('Cache.DefinitionImpl', null);
            //$config->set('Core.DefinitionCache', null);
            //$config->set('Cache.SerializerPath', '/home/user/absolute/path');
            $this->setConfig($config);
        }
        return $this->_config;
    }

    public function setConfig(HTMLPurifier_Config $config)
    {
        $this->_config = $config;
        $this->_purifier = null;
        return $this;
    }

    public function getPurifier()
    {
        if (null === $this->_purifier) {
            $this->setPurifier(new HTMLPurifier($this->getConfig()));
        }
        return $this->_purifier;
    }

    public function setPurifier(HTMLPurifier $purifier)
    {
        $this->_purifier = $purifier;
        return $this;
    }
}