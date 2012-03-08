<?php
class HausDesign_Form_Bootstrap extends HausDesign_Form
{
    function __construct($options = null)
    {
        // Set the element decorators
        $this->_elementDecorators = array(
            'ViewHelper',
            array('Description', array('tag' => 'p', 'class' => 'help-block')),
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'controls')),
            array('Label', array('class' => 'control-label')),
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group')),
            //array('HtmlTag', array('tag' => 'div', 'class' => 'clear', 'placement' => 'APPEND'))
        );

        // Set the decorators
        $this->_decorators = array(
            array('BootstrapDescription', array('escape' => false)),
            'BootstrapFormErrorMessages',
            'FormElements',
            'Form'
        );

        // Call the constructor of the parent object
        parent::__construct($options);

        // Set the decorators
        $this->setDecorators(array(
            array('BootstrapDescription', array('escape' => false)),
            'BootstrapFormErrorMessages',
            'FormElements',
            'Form'
        ));
    }
}