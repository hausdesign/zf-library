<?php
class HausDesign_View_Helper_BootstrapInformation extends HausDesign_View_Helper_BootstrapAlert
{
    /**
     * 
     * 
     * @param string|array $messages
     * @param array $attribs
     * 
     * @return string
     */
    public function bootstrapInformation($messages = null)
    {
        return parent::bootstrapAlert($messages, array(
            'class' => 'alert-success'
        ));
    }
}