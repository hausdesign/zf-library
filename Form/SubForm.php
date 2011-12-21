<?php
class HausDesign_Form_SubForm extends Zend_Form_SubForm
{
    function __construct($options = null)
    {
        $this->addPrefixPath('ZendX_JQuery_Form_', 'ZendX/JQuery/Form/');
        $this->addPrefixPath('HausDesign_JQuery_Form_', 'HausDesign/JQuery/Form/');
        $this->addPrefixPath('HausDesign_Form', 'HausDesign/Form/');
        $this->addPrefixPath('Application_Form', 'Application/Form/');

        parent::__construct($options);
    }

    public function createElement($type, $name, $options = null)
    {
        if ((null === $options) || !is_array($options)) {
            $options = array('prefixPath' => array(
                array('prefix' => 'ZendX_', 'path' => 'ZendX/'),
                array('prefix' => 'HausDesign_', 'path' => 'HausDesign/'),
                array('prefix' => 'Application_', 'path' => 'Application/')
            ));
        } elseif (is_array($options)) {
            if (array_key_exists('prefixPath', $options)) {
                if (isset($options['prefixPath']['prefix'])) {
                    $options['prefixPath'] = array(
                        array('prefix' => $options['prefixPath']['prefix'], 'path' => $options['prefixPath']['path']),
                        array('prefix' => 'ZendX_', 'path' => 'ZendX/'),
                        array('prefix' => 'HausDesign_', 'path' => 'HausDesign/'),
                        array('prefix' => 'Application_', 'path' => 'Application/')
                    );
                } else {
                    $options['prefixPath'][] = array(
                        array('prefix' => 'ZendX_', 'path' => 'ZendX/'),
                        array('prefix' => 'HausDesign_', 'path' => 'HausDesign/'),
                        array('prefix' => 'Application_', 'path' => 'Application/'),
                    );
                }
            } else {
                $options['prefixPath'] = array(
                    array('prefix' => 'ZendX_', 'path' => 'ZendX/'),
                    array('prefix' => 'HausDesign_', 'path' => 'HausDesign/'),
                    array('prefix' => 'Application_', 'path' => 'Application/')
                );
            }
        }

        return parent::createElement($type, $name, $options);
    }

    public function setErrorClass()
    {
        foreach ($this->getSubForms() as $subForm) {
            if ($subForm->isErrors()) {
                $class = $subForm->getAttrib('class');

                if (($class !== null) && ($class != '')) {
                    $class .= ' error';
                } else {
                    $class = 'error';
                }

                $subForm->setAttrib('class', $class);

                $subForm->setErrorClass();
            }
        }
    }

    public function setRequiredSuffixToElements($requiredSuffix = ' *')
    {
        foreach ($this->getSubForms() as $subForm) {
            foreach ($subForm->getElements() as $element) {
                $decorator = $element->getDecorator('Label');
                try {
                    if (($decorator !== null) && ($decorator !== false)) {
                        $decorator->setRequiredSuffix(' *');
                    }
                } catch(Exception $ex) {
                    Zend_Debug::dump($ex->getMessage());
                }
            }
        }

        foreach ($this->getElements() as $element) {
            $decorator = $element->getDecorator('Label');
            try {
                if (($decorator !== null) && ($decorator !== false)) {
                    $decorator->setRequiredSuffix(' *');
                }
            } catch(Exception $ex) {
                Zend_Debug::dump($ex->getMessage());
            }
        }
    }
}