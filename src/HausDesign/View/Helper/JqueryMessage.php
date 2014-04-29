<?php
class HausDesign_View_Helper_JqueryMessage extends Zend_View_Helper_HtmlElement
{
    /**
     * 
     * 
     * @param string|array $messages
     * @param array $attribs
     * 
     * @return string
     */
    public function jqueryMessage($messages = null, array $attribs = array())
    {
        $markup = '';

        $icon = 'ui-icon';
        if (array_key_exists('icon', $attribs)) {
            $icon .= ' ' . $attribs['icon'];
        }

        $class = 'ui-widget ui-message ui-corner-all';
        if (array_key_exists('class', $attribs)) {
            $class .= ' ' . $attribs['class'];
        }

        if (($messages !== null) && ($messages != '') && (! is_array($messages))) {
            $messages = array($messages);
        }

        $elementTag = 'span';
        $elementAttribs = array('class' => 'ui-message-description');

        if (count($messages) == 1) {
            $markup .= $this->view->htmlTag($elementTag, $elementAttribs, array_shift($messages));
        } else {
            if (Zend_Registry::isRegistered('Zend_Translate')) {
                $translate = Zend_Registry::get('Zend_Translate');
                $markup .= $this->view->htmlTag($elementTag, $elementAttribs, ucfirst($translate->translate('textErrorsOccurred')));
            } else {
                $markup .= $this->view->htmlTag($elementTag, $elementAttribs, 'Error');
            }

            if (count($messages) >= 1) {
                $markup .= $this->view->htmlList($messages);
            }
        }

        $markup = $this->view->htmlTag('span', array('class' => $icon)) . $markup;
        $markup = $this->view->htmlTag('div', array('class' => $class), $markup);

        return $markup;
    }
}