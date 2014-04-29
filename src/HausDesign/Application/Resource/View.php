<?php
class HausDesign_Application_Resource_View extends Zend_Application_Resource_View
{
    public function init()
    {
        $view = parent::init();

        $options = $this->getOptions();
        if (array_key_exists('variables', $options)) {
            if (is_array($options['variables'])) {
                foreach ($options['variables'] as $key => $value) {
                    $view->assign($key, $value);
                }
            } else {
                $view->assign($options['variables']);
            }
        }

        $viewRenderer = new HausDesign_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

        return $view;
    }
}
