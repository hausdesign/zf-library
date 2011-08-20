<?php
class HausDesign_Controller_Plugin_Locale extends Zend_Controller_Plugin_Abstract
{
    /**
     * Detect the locale
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
    	$language = $request->getParam('language_code');

    	$locale = new Zend_Locale($language);
    	Zend_Locale::setDefault($language);

    	Zend_Registry::set('Zend_Locale', $locale);
    }
}