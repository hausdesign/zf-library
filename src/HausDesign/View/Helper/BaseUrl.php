<?php
class HausDesign_View_Helper_BaseUrl extends Zend_View_Helper_BaseUrl
{
    public function baseUrl($file = null, $includeApplication = true, $includeLanguage = false)
    {
        $baseUrl = parent::baseUrl();

        if (! $includeApplication) {
            $application = Zend_Controller_Front::getInstance()->getParam('application');

            $baseUrl = preg_replace('/\/' . $application . '$/', '', rtrim($baseUrl, '/'));
        }

        if ($includeLanguage) {
            if (Zend_Registry::isRegistered('domain_site_language')) {
                $domainSiteLanguage = Zend_Registry::get('domain_site_language');
                $baseUrl = $domainSiteLanguage->getDomainName(false) . $baseUrl;
            }
        }

        if ((! is_null($file)) && ($file != '')) {
            $file = ltrim($file, '/\\');
            $baseUrl .= '/' . $file;
        }

        return $baseUrl;
    }
}