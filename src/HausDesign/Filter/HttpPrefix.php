<?php
/**
 * Filter for prefixing URIs with "http://"
 */
class HausDesign_Filter_HttpPrefix implements Zend_Filter_Interface
{
    /**
     * @see    Zend_Filter_Interface::filter()
     * @return string
     */
    public function filter($value)
    {
        if (empty($value)) {
            return $value;
        } elseif (!preg_match('(^https?://)', $value)) {
            return 'http://' . $value;
        } else {
            return $value;
        }
    }
}