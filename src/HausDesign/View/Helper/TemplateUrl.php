<?php
class HausDesign_View_Helper_TemplateUrl extends Zend_View_Helper_Abstract
{
    protected $_templateUrl = null;

    public function templateUrl($file = null)
    {
        // Get baseUrl
        $templateUrl = $this->getTemplateUrl();

        if ((! is_null($file)) && ($file != '')) {
            $file = ltrim($file, '/');
        }

        return $templateUrl . '/' . $file;
    }

    public function getTemplateUrl()
    {
        //if ($this->_templateUrl === null) {
            $publicPath = PUBLIC_PATH;
            $publicPath = str_replace('/', DIRECTORY_SEPARATOR, $publicPath);
            $publicPath = str_replace('\\', DIRECTORY_SEPARATOR, $publicPath);
            $layoutPath = $this->view->layout()->getLayoutPath();
            $layoutPath = str_replace('/', DIRECTORY_SEPARATOR, $layoutPath);
            $layoutPath = str_replace('\\', DIRECTORY_SEPARATOR, $layoutPath);

            $templateUrl = preg_replace('#^' . str_replace('\\', '\\\\', $publicPath) . '#', '', $layoutPath);

            $templateUrl = str_replace('\\', '/', $templateUrl); 

            $this->_templateUrl = rtrim($this->view->baseUrl($templateUrl, false), '/');
        //}

        return $this->_templateUrl;
    }
}