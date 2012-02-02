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
        // Set the element decorators
        $this->_elementDecorators = array(
            'ViewHelper',
            array('Description', array('tag' => 'span', 'class' => 'help-block')),
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'input')),
            array('Label'),
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'clearfix')),
            //array('HtmlTag', array('tag' => 'div', 'class' => 'clear', 'placement' => 'APPEND'))
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

    /**
     * (non-PHPdoc)
     * @see Zend_Form::render()
     */
    public function render(Zend_View_Interface $view = null)
    {
        // Add a hidden element to the form with the form name
        if (($this->getName() !== null) && ($this->getName() != '')) {
            $this->addElement('hidden', 'form_name', array('value' => $this->getName(), 'decorators' => array('ViewHelper')));
        }

        // Set the name of the form as form class
        $this->addElement('hidden', 'form_class', array('value' => get_class($this), 'decorators' => array('ViewHelper'), 'ignore' => true));

        // Set the required suffix to all form elements
        $this->setRequiredSuffixToElements(' *');

        return parent::render($view);
    }
}

/*
class HausDesign_Form_Bootstrap extends HausDesign_Form
{
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
            array('Description', array('tag' => 'span', 'class' => 'help-block')),
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
*/