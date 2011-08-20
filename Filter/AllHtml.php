<?php
class HausDesign_Filter_AllHtml implements Zend_Filter_Interface
{
    public function filter($value)
    {
        $filterChain = new Zend_Filter();

        $htmlFilter = new HausDesign_Filter_HtmlPurifier();
        $config = $htmlFilter->getConfig();
        $config->set('AutoFormat', 'AutoParagraph', true);
        $config->set('AutoFormat', 'Linkify', true);
        $filterChain->addFilter($htmlFilter);

        //$geshiFilter = new HausDesign_Filter_Geshi();
        //$filterChain->addFilter($geshiFilter);

        return $filterChain->filter($value);
    }
}