<?php
class HausDesign_View_Helper_FormJQueryMultiSelect extends Zend_View_Helper_FormSelect
{
    public function formJQueryMultiselect($name, $value = null, $attribs = null,
        $options = null, $listsep = "<br />\n")
    {
        if (array_key_exists('jQueryParams', $attribs)) {
            $jQueryParams = $attribs['jQueryParams'];
            $jQueryParams = ZendX_JQuery::encodeJson($jQueryParams);
            unset($attribs['jQueryParams']);
        } else {
            $jQueryParams = array();
        }

        $js = sprintf('%s("#%s").multiselect(%s);',
            ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(),
            $attribs['id'],
            $jQueryParams
        );

        $this->view->headScript()->appendFile($this->view->baseUrl('/scripts/jquery-plugins/multiselect/src/jquery.multiselect.js', false));
        $this->view->headLink()->prependStylesheet($this->view->baseUrl('/scripts/jquery-plugins/multiselect/jquery.multiselect.css', false), 'screen');
        
        $this->view->jQuery()->enable()->uiEnable()->addOnLoad($js);

        return parent::formSelect($name, $value, $attribs, $options, $listsep);
    }
}