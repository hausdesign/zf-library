<?php
class HausDesign_View_Helper_ParseLinks extends Zend_View_Helper_Abstract
{
    public function parseLinks($value)
    {
        // Parse links (http(s)://.../)
        $value = preg_replace('/(https{0,1}:\/\/[\w\-\.\/#?&=]*)/', '<a href="$1">$1</a>', $value);

        return $value;
    }
}