<?php
class HausDesign_View_Helper_BaseUrl extends Zend_View_Helper_BaseUrl
{
    public function baseUrl($file = null, $includeApplication = true, $includeLanguage = true)
    {
        $baseUrl = parent::baseUrl();

        if (! $includeApplication) {
            $application = Zend_Controller_Front::getInstance()->getParam('application');

            $baseUrl = preg_replace('/\/' . $application . '$/', '', rtrim($baseUrl, '/'));
        }

        if ((! is_null($file)) && ($file != '')) {
            $file = ltrim($file, '/\\');
            $baseUrl .= '/' . $file;
        }

        return $baseUrl;
    }
}