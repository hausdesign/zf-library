<?php
class HausDesign_Application_Bootstrap_Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Intialize the module autoloader in order to allow model loading
     *
     * @access protected
     * @return Zend_Application_Module_Autoloader
     */
    protected function _initAutoload()
    {
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Application',
            'basePath' => APPLICATION_PATH));
        return $moduleLoader;
    }

    public function _initApplication()
    {
        $this->bootstrap('Frontcontroller');
        $frontController = $this->getResource('Frontcontroller');

        $baseUrl = trim($frontController->getBaseUrl(), '/');
        $applicationBaseUrl = trim($this->getApplication()->getBaseUrl(), '/');

        if (is_null($baseUrl)) $baseUrl = '';
        if (is_null($applicationBaseUrl)) $applicationBaseUrl = '';

        if ($applicationBaseUrl != '') {
            if ($baseUrl != '') {
                $baseUrl .= '/';
            }
            $baseUrl .= $applicationBaseUrl;
        }

        if ((! is_null($baseUrl)) && ($baseUrl != '')) {
            $frontController->setBaseUrl('/' . $baseUrl . '/');
        }
    }
}