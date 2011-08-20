<?php
class HausDesign_View_Helper_JqueryError extends HausDesign_View_Helper_JqueryMessage
{
    /**
     * 
     * 
     * @param string|array $messages
     * @param array $attribs
     * 
     * @return string
     */
    public function jqueryError($messages = null)
    {
        return parent::jqueryMessage($messages, array(
            'icon' => 'ui-icon-alert',
        	'class' => 'ui-state-error'
        ));
    }
}