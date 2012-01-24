<?php
class HausDesign_Form_Decorator_TabNavigation extends Zend_Form_Decorator_Abstract
{
    /**
     * Render form elements
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        $form    = $this->getElement();
        if (!$form instanceof Zend_Form_SubForm) {
            return $content;
        }

        $return = '';
        $return .= '<ul class="tabs" data-tabs="tabs">';

        foreach ($form as $element) {
            $class = $element->getAttrib('class');
            $return .= '<li' . (($class !== null) ? ' class="' . $class . '"' : '') . '><a href="#tab-' . $element->getId() . '">' . $element->getLegend() . '</a></li>';
        }

        $return .= '</ul>';

        return $return . '<div class="tab-content">' . $content . '</div>';
    }
}