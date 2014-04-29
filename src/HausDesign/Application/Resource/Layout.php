<?php
class HausDesign_Application_Resource_Layout extends Zend_Application_Resource_Layout
{
    public function init()
    {
        $layout = parent::init();

        $options = $this->getOptions();
        if (array_key_exists('variables', $options)) {
            if (is_array($options['variables'])) {
                foreach ($options['variables'] as $key => $value) {
                    $layout->assign($key, $value);
                }
            } else {
                $layout->assign($options['variables']);
            }
        }

        return $layout;
    }
}
