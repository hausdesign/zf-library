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

        $formErrorMessages = $form->getErrorMessages();

        if ((! $form->isErrors()) && (count($formErrorMessages) <= 0)) {
            return $content;
        }

        $showFormErrors = $this->getOption('showFormErrors');
        if (is_null($showFormErrors)) {
            $showFormErrors = true;
        }

        if ((! $showFormErrors) && (count($formErrorMessages) <= 0)) {
            return $content;
        }

        if (count($formErrorMessages) == 0) {
            if (Zend_Registry::isRegistered('Zend_Translate')) {
                $translate = Zend_Registry::get('Zend_Translate');
                $formErrorMessages[] = ucfirst($translate->translate('textErrorsOccurred'));
            } else {
                $formErrorMessages[] = ucfirst($translate->translate('Error'));
            }
        }

        $markup = $view->bootstrapError($formErrorMessages);

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
