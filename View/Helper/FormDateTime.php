<?php
class HausDesign_View_Helper_FormDateTime extends Zend_View_Helper_FormText
{
    /**
     * Generates a 'calendar' element.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     *
     * @param mixed $value The element value.
     *
     * @param array $attribs Attributes for the element tag.
     *
     * @return string The element XHTML.
     */
    public function formDateTime($name, $value = null, $attribs = null)
    {
        $locale = Zend_Registry::getInstance()->Zend_Locale;

        $this->view->headScript()->appendFile($this->view->baseUrl('/scripts/jquery-ui-plugins/jquery.ui.timepicker/js/jquery.ui.timepicker.js', false));
        $this->view->headLink()->appendStylesheet($this->view->baseUrl('/scripts/jquery-ui-plugins/jquery.ui.timepicker/css/jquery.ui.timepicker.css', false), 'screen');

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

        $js = sprintf('%s("#%s").datetimepicker(%s);',
                ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(),
                $attribs['id'],
                $params
        );

        $jquery->addOnLoad($js);

        $xhtml = '';
        $xhtml .= $this->formText($name, $value, $attribs);

        return $xhtml;
    }
}