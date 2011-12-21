<?php
class HausDesign_Form_Decorator_BootstrapFormErrorMessages extends Zend_Form_Decorator_Abstract
{
    /**
     * Render errors
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        $form = $this->getElement();
        if (!$form instanceof Zend_Form) {
            return $content;
        }

        $view = $form->getView();
        if (null === $view) {
            return $content;
        }

        $this->initOptions();

        if ((! $form->isErrors()) && (count($form->getErrorMessages()) <= 0)) {
            return $content;
        }

        $showFormErrors = $this->getOption('showFormErrors');
        if (is_null($showFormErrors)) {
            $showFormErrors = true;
        }

        if ((! $showFormErrors) && (count($form->getErrorMessages()) <= 0)) {
            return $content;
        }

        $markup = $view->bootstrapError($form->getErrorMessages());

        switch ($this->getPlacement()) {
            case self::APPEND:
                return $content . $this->getSeparator() . $markup;
            case self::PREPEND:
                return $markup . $this->getSeparator() . $content;
        }
    }

    /**
     * Initialize options
     *
     * @return void
     */
    public function initOptions()
    {
        $this->getPlacement();
        $this->getSeparator();
    }
}
