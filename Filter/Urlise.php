<?php
class HausDesign_Filter_Urlise implements Zend_Filter_Interface
{
    public function filter($value)
    {
        $filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StripTags());
        $filterChain->addFilter(new Zend_Filter_StringTrim());
        $filterChain->addFilter(new Zend_Filter_StringToLower());
        $value = $filterChain->filter($value);

        // replace accented characters
        $replaceArray = array(
            '�' => 'a', '�' => 'e', '�' => 'i', '�' => 'o', '�' => 'u',
            '�' => 'a', '�' => 'e', '�' => 'i', '�' => 'o', '�' => 'u',
            '�' => 'a', '�' => 'e', '�' => 'i', '�' => 'o', '�' => 'u',
            '�' => 'a', '�' => 'e', '�' => 'i', '�' => 'o', '�' => 'u',
            '�' => 'c', '�' => 'ae', '�' => 'oe', '�' => 'a', '�' => 'o',
            '@' => 'at', '�' => 'copyright', '�' => 'euro', '�' => 'tm', '-' => ' ', '/' => ' ', '\\' => ' '
        );

        $value = str_replace(array_keys($replaceArray), array_values($replaceArray), $value);

        $valueTemp = '';
        $allowedChars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '-', '_', ' ');

        $valueTemp = '';
        for($i = 0; $i < strlen($value); $i++) { 
            if (in_array($value[$i], $allowedChars)) {
               $valueTemp .= $value[$i];
            }
        }
        $value = $valueTemp;

        // replace double spaces
        $value = preg_replace('/\s+/', ' ', $value);

        $filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim());
        $filterChain->addFilter(new Zend_Filter_Word_SeparatorToDash());
        $value = $filterChain->filter($value);

        return $value;
    }
}