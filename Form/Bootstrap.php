<?php
class HausDesign_Form_Bootstrap extends HausDesign_Form
{
    /**
     * Constructor
     *
     * @param array $options
     */
    function __construct($options = null)
    {
        // Set the decorators
        $this->_decorators = array(
            array('BootstrapDescription', array('escape' => false)),
            'BootstrapFormErrorMessages',
            'FormElements',
            'Form'
        );

        // Set the decorators
        $this->setDecorators(array(
            array('BootstrapDescription', array('escape' => false)),
            'BootstrapFormErrorMessages',
            'FormElements',
            'Form'
        ));

        // Set the element decorators
        $this->_elementDecorators = array(
            'ViewHelper',
            'Description',
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'input')),
            array('Label'),
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'clearfix')),
            //array('HtmlTag', array('tag' => 'div', 'class' => 'clear', 'placement' => 'APPEND'))
        );

        // Call the constructor of the parent object
        parent::__construct($options);
    }
}