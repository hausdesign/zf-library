<?php
class HausDesign_Controller_Plugin_Language extends Zend_Controller_Plugin_Abstract
{
    /**
     * Routes all (public) request to unknown modules to the standard
     * Cms module / Page controller
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        //Set the locale at Zend_Translate
        $locale = Zend_Registry::get('Zend_Locale');

        if ($request->getParam('language') != null) {
            $locale->setLocale($request->getParam('language'));
        }
    }
}