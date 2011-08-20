<?php
class HausDesign_View_Helper_FullName extends Zend_View_Helper_BaseUrl
{
    public function fullName($firstName = null, $insertion = null, $lastName = null)
    {
        $parts = array();

        if ((! (is_null($firstName))) && ($firstName != '')) { $parts[] = $firstName; }
        if ((! (is_null($insertion))) && ($insertion != '')) { $parts[] = $insertion; }
        if ((! (is_null($lastName))) && ($lastName != '')) { $parts[] = $lastName; }

        return implode(' ', $parts);
    }
}