<?php
class HausDesign_View_Helper_BootstrapAlert extends Zend_View_Helper_HtmlElement
{
    /**
     * Alert in Bootstrap style
     * 
     * @param string|array $messages
     * @param array $attribs
     * 
     * @return string
     */
    public function bootstrapAlert($messages = null, array $attribs = array())
    {
        $markup = '';
        
        if (($messages !== null) && ($messages != '') && (! is_array($messages))) {
            $messages = array($messages);
        }

        $class = '';
        if (array_key_exists('class', $attribs)) {
            $class = $attribs['class'];
        }

        switch (count($messages)) {
            case 0:
                if (Zend_Registry::isRegistered('Zend_Translate')) {
                    $translate = Zend_Registry::get('Zend_Translate');
                    $markup .= $this->_renderMessage(ucfirst($translate->translate('textErrorsOccurred')), $class);
                } else {
                    $markup .= $this->_renderMessage('Error', $class);
                }
                break;
            case 1:
                $markup .= $this->_renderMessage(array_shift($messages), $class);
                break;
            default:
                foreach ($messages as $message) {
                    $markup .= $this->_renderMessage($message, $class);
                }
                break;
        }

        return $markup;
    }

    /**
     * Render the message
     * 
     * @param string $message
     * @param string $class
     */
    protected function _renderMessage($message, $class)
    {
        $divClass = '';
        $divClass .= 'alert alert-block';
        if ($class != '') $divClass .= ' ' . $class;
        $divClass .= ' fade in';

        $return = '';

        $return .= $this->view->htmlTag('a', array('href' => '#', 'class' => 'close', 'data-dismiss' => 'alert'), '&times;');
        $return .= $this->view->htmlTag('p', array(), $message);
        $return = $this->view->htmlTag('div', array('class' => $divClass, 'data-alert' => 'alert'), $return);

        return $return;
    }
}