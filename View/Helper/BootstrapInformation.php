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
        if (($messages !== null) && ($messages != '')) {
            if (! is_array($messages)) {
                $messages = array($messages);
            }

            if (count($messages) > 0) {
                return parent::bootstrapAlert($messages, array(
            		'class' => 'success'
                ));
            }
        }

        return '';
    }
}