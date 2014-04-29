<?php
class HausDesign_Form_Jquery extends HausDesign_Form
{
    /**
     * Constructor
     *
     * @param array $options
     */
    function __construct($options = null)
    {
        // Call the constructor of the parent object
        parent::__construct($options);

        $this->setDecorators(array(
            array('JqueryDescription', array('escape' => false)),
            'JqueryFormErrorMessages',
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),
            'Form'
        ));
    }
}