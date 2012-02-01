<?php
class HausDesign_Form_Bootstrap_Tab extends HausDesign_Form_SubForm
{
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

        parent::__construct($options);
    }

    /**
     * Load the default decorators
     *
     * @return Zend_Form_SubForm
     */
    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('FormElements')
                 ->addDecorator('FormWrapper', array('tag' => 'div', 'class' => 'tab-pane'));
        }

        return $this;
    }
}