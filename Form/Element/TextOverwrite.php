<?php
class HausDesign_Form_Element_TextOverwrite extends Zend_Form_Element_Xhtml
{
    /**
     * Is the checkbox checked?
     * @var bool
     */
    public $overwrite = false;

    /**
     * Default form view helper to use for rendering
     * @var string
     */
    public $helper = 'formTextOverwrite';

    public function setValue($value)
    {
        if (is_array($value)) {
            if (isset($value['value'])) {
                parent::setValue($value['value']);
            }

            if (isset($value['overwrite'])) {
                if (($value['overwrite'] == '1') || ($value['overwrite'] === true)) {
                    $this->overwrite = true;
                } else {
                    $this->overwrite = false;
                }
            }
        } else {
            parent::setValue($value);
        }
    }
}