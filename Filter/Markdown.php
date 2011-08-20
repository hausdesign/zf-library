<?php
class HausDesign_Filter_Markdown implements Zend_Filter_Interface
{
    public function filter($value)
    {
        $filterChain = new Zend_Filter();

        //$geshiFilter = new HausDesign_Filter_Geshi();
        //$filterChain->addFilter($geshiFilter);

        $value = $filterChain->filter($value);

        require_once 'markdown/markdown.php';
        return Markdown($value);
    }
}