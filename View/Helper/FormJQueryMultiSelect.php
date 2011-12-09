<?php
class HausDesign_View_Helper_FormJQueryMultiSelect extends Zend_View_Helper_FormSelect
{
    public function formJQueryMultiselect($name, $value = null, $attribs = null,
        $options = null, $listsep = "<br />\n")
    {
        if (array_key_exists('jqueryParams', $attribs)) {
            $jqueryParams = $attribs['jqueryParams'];
            $jqueryParams = ZendX_JQuery::encodeJson($jqueryParams);
            unset($attribs['jqueryParams']);
        } else {
            $jqueryParams = array();
        }

        $js = sprintf('%s("#%s").multiselect(%s);',
            ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(),
            $attribs['id'],
            $jqueryParams
        );

        $this->view->headScript()->appendFile($this->view->baseUrl('/scripts/jquery-plugins/multiselect/src/jquery.multiselect.js', false));
        $this->view->headLink()->prependStylesheet($this->view->baseUrl('/scripts/jquery-plugins/multiselect/jquery.multiselect.css', false), 'screen');
        
        $this->view->jQuery()->enable()->uiEnable()->addOnLoad($js);

        return parent::formSelect($name, $value, $attribs, $options, $listsep);
    }
}