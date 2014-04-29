<?php
class HausDesign_Validate_Uri extends Zend_Validate_Abstract
{
    const INVALID_URI = 'invalidUri';

    protected $_messageTemplates = array(
        self::INVALID_URI => "Invalid URI",
    );

    public function isValid($value)
    {
        $this->_setValue($value);

        // Validate the URI
        $valid = Zend_Uri::check($value);

        // Return validation result TRUE|FALSE   
        if ($valid)  {
            return true;
        } else {
            $this->_error(self::INVALID_URI);
            return false;
        }
    }
}