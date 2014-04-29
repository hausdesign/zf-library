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
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u',
            'ä' => 'a', 'ë' => 'e', 'ï' => 'i', 'ö' => 'o', 'ü' => 'u',
            'â' => 'a', 'ê' => 'e', 'î' => 'i', 'ô' => 'o', 'û' => 'u',
            'ç' => 'c', 'æ' => 'ae', 'œ' => 'oe', 'å' => 'a', 'ø' => 'o',
            '@' => 'at', '©' => 'copyright', '€' => 'euro', '™' => 'tm', '-' => ' ', '/' => ' ', '\\' => ' '
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