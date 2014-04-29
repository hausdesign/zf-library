<?php
class HausDesign_Application_Resource_Cmsrouter extends Zend_Application_Resource_Router
{
    /**
     * Retrieve router object
     *
     * @return Zend_Controller_Router_Rewrite
     */
    public function getRouter()
    {
        // Get the domain name
        $domain = $_SERVER['HTTP_HOST'];
        $modelDomain = new HausDesign_Model_Cms_Domain();
        $domain = $modelDomain->fetchRowByDomain($domain);

        if (null === $this->_router) {
            $bootstrap = $this->getBootstrap();
            $bootstrap->bootstrap('FrontController');
            $this->_router = $bootstrap->getContainer()->frontcontroller->getRouter();

            $options = $this->getOptions();
            if (!isset($options['routes'])) {
                $options['routes'] = $domain->getRoutes($bootstrap->getResource('cachemanager')->getCache('database'));
            }

            if (isset($options['chainNameSeparator'])) {
                $this->_router->setChainNameSeparator($options['chainNameSeparator']);
            }

            if (isset($options['useRequestParametersAsGlobal'])) {
                $this->_router->useRequestParametersAsGlobal($options['useRequestParametersAsGlobal']);
            }

            $this->_router->addConfig(new Zend_Config($options['routes']));
        }

        return $this->_router;
    }
}