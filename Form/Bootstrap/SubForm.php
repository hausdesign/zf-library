<?php
class HausDesign_Form_Bootstrap_SubForm extends HausDesign_Form_SubForm
{
    function __construct($options = null)
    {
        // Set the decorators
        $this->_decorators = array(
            array('BootstrapDescription', array('escape' => false)),
            'FormElements',
            'Fieldet'
        );
    
        // Set the decorators
        $this->setDecorators(array(
            array('BootstrapDescription', array('escape' => false)),
            'FormElements',
            'Fieldset'
        ));
    
        // Set the element decorators
        $this->_elementDecorators = array(
            'ViewHelper',
            array('Description', array('tag' => 'span', 'class' => 'help-block')),
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'controls')),
            array('Label'),
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'control-group')),
            //array('HtmlTag', array('tag' => 'div', 'class' => 'clear', 'placement' => 'APPEND'))
        );
    
        // Call the constructor of the parent object
        parent::__construct($options);
    }
}