<?php
class HausDesign_View_Helper_FormJQueryMultiSelect extends Zend_View_Helper_FormSelect
{
    public function formJQueryMultiSelect($name, $value = null, $attribs = null,
        $options = null, $listsep = "<br />\n")
    {
        $this->view->headScript()->appendFile($this->view->baseUrl('/scripts/jquery-plugins/multiselect/src/jquery.multiselect.js', false));
        $this->view->headLink()->appendStylesheet($this->view->baseUrl('/scripts/jquery-plugins/multiselect/jquery.multiselect.css', false), 'screen');

        $params = array();
        if (array_key_exists('jQueryParams', $attribs)) {
            $params = $attribs['jQueryParams'];
            unset($attribs['jQueryParams']);
        }

        $jquery = $this->view->jQuery();
        $jquery->enable()
               ->uiEnable();

        // TODO: Allow translation of DatePicker Text Values to get this action from client to server
        $params = ZendX_JQuery::encodeJson($params);

        $js = sprintf('%s("#%s").multiselect(%s);',
                ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(),
                $attribs['id'],
                $params
        );

        $jquery->addOnLoad($js);

        $xhtml = '';
        $xhtml .= $this->formSelect($name, $value, $attribs, $options, $listsep);

        return $xhtml;
    }
}