<?php
class HausDesign_Form_Decorator_BootstrapDescription extends Zend_Form_Decorator_Abstract
{
    /**
     * Render description
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

        if (($form->getDescription() === null) || ($form->getDescription() == '')) {
            return $content;
        }

        $markup = $view->bootstrapInformation($form->getDescription());

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