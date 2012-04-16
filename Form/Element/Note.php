<?php
class HausDesign_Form_Element_Note extends Zend_Form_Element_Hidden
{
    /**
     * Default form view helper to use for rendering
     * @var string
     */
    public $helper = 'formNote';

    protected $_ignore = true;

    public function setValue($value)
    {
        if (null === $this->_value) {
            parent::setValue($value);
        }

        return $this;
    }
}