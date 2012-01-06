<?php
class HausDesign_View_Helper_FormTextOverwrite extends Zend_View_Helper_FormElement
{
    /**
     * Generates a 'text' element.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are used in place of added parameters.
     *
     * @param mixed $value The element value.
     *
     * @param array $attribs Attributes for the element tag.
     *
     * @return string The element XHTML.
     */
    public function formTextOverwrite($name, $value = null, $attribs = null)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disabled

        $viewHelperFormCheckbox = new Zend_View_Helper_FormCheckbox();
        $viewHelperFormCheckbox->setView($this->view);

        $viewHelperFormElement = new Zend_View_Helper_FormText();
        $viewHelperFormElement->setView($this->view);

        $checked = '';
        if ($attribs['overwrite']) {
            $checked = 'checked';
            $disabled = true;
            unset($attribs['overwrite']);
        } else {
            $disabled = false;
            if (isset($attribs['class']) && $attribs['class'] != '') { 
                $attribs['class'] = $attribs['class'] . ' disabled';
            } else {
                $attribs['class'] = 'disabled';
            }
        }

        $separator = '';
        if (isset($attribs['separator'])) {
            $separator = $attribs['separator'];
            unset($attribs['separator']); 
        }

        $xhtml = '<div class="input-prepend">';
        $xhtml .= '<label class="add-on">';
        $xhtml .= $viewHelperFormCheckbox->formCheckbox($name . '[overwrite]', null, array_merge($attribs, array('checked' => $checked, 'class' => 'checkbox text-overwrite-checkbox')));
        $xhtml .= '</label>';
        $xhtml .= ' ' . $separator;
        if (! $disabled) {
            $xhtml .= $viewHelperFormElement->formText($name . '[value]', $value, array_merge($attribs, array('disabled' => 'disabled')));
        } else {
            $xhtml .= $viewHelperFormElement->formText($name . '[value]', $value, $attribs);
        }
        $xhtml .= '</div>';

        return $xhtml;
    }
}