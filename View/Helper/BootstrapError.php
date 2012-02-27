<?php
class HausDesign_View_Helper_BootstrapError extends HausDesign_View_Helper_BootstrapAlert
{
    /**
     * 
     * 
     * @param string|array $messages
     * @param array $attribs
     * 
     * @return string
     */
    public function bootstrapError($messages = null)
    {
        return parent::bootstrapAlert($messages, array(
            'class' => 'alert-error'
        ));
    }
}