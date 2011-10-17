<?php
class HausDesign_View_Helper_GetUrl extends Zend_View_Helper_Abstract
{
    public function getUrl()
    {
        $return = '';

        $protocol = ((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) ? 'https' : 'http');
        $return .= $protocol;
        $return .= '://';
        $return .= $_SERVER['HTTP_HOST'];
        $return .= $_SERVER['REQUEST_URI'];

        return $return;
    }
}