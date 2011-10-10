<?php
class HausDesign_Form_Element_JQueryMultiSelect extends Zend_Form_Element_Select
{
    /**
     * 'multiple' attribute
     * @var string
     */
    public $multiple = 'multiple';

    /**
     * Use formSelect view helper by default
     * @var string
     */
    public $helper = 'formJQueryMultiSelect';

    /**
     * Multiselect is an array of values by default
     * @var bool
     */
    protected $_isArray = true;
}
