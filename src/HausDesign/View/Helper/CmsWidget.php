<?php
class HausDesign_View_Helper_CmsWidget extends Zend_View_Helper_Abstract
{
    public function cmsWidget($value)
    {
        $configuration = Zend_Registry::get('config');
        if (isset($configuration['modules']) && isset($configuration['modules']['cms_widgets'])) {
            $widgetPlaceholders = $configuration['modules']['cms_widgets'];

            foreach ($widgetPlaceholders as $widgetPlaceholder => $mvc) {
                $value = preg_replace_callback('/{{(' . $widgetPlaceholder . ')([^}]*)}}/', 'HausDesign_View_Helper_CmsWidget::_replace', $value);
            }
        }

        return $value;
    }

    private static function _replace($matches)
    {
        $replaceValue = trim($matches[2], '|');

        $params = array();
        foreach (preg_split('/\|/s', $replaceValue) as $param) {
            $paramValues = preg_split('/\=/s', $param);
    
            if (count($paramValues >= 2)) {
                $params[$paramValues[0]] = $paramValues[1];
            } elseif (count($paramValues >= 1)) {
                $params[$paramValues[0]] = '';
            }
        }

        $configuration = Zend_Registry::get('config');
        $widgetPlaceholders = $configuration['modules']['cms_widgets'];

        $module = strtolower('mod' . $widgetPlaceholders[$matches[1]]['module']);
        $controller = $widgetPlaceholders[$matches[1]]['controller'];
        $action = $widgetPlaceholders[$matches[1]]['action'];

        $frontController = Zend_Controller_Front::getInstance();
        $layoutPlugin = $frontController->getPlugin('Zend_Layout_Controller_Plugin_Layout');
        $view = $layoutPlugin->getLayout()->getView();

        return $view->action($action, $controller, $module, $params);
    }
}