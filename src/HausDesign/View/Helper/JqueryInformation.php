<?php
class HausDesign_View_Helper_JqueryInformation extends HausDesign_View_Helper_JqueryMessage
{
    /**
     * 
     * 
     * @param string|array $messages
     * @param array $attribs
     * 
     * @return string
     */
    public function jqueryInformation($messages = null)
    {
        if (($messages !== null) && ($messages != '')) {
            if (! is_array($messages)) {
                $messages = array($messages);
            }

            if (count($messages) > 0) {
                return parent::jqueryMessage($messages, array(
                    'icon' => 'ui-icon-info',
            		'class' => 'ui-state-highlight'
                ));
            }            
        }

        return '';
    }
}