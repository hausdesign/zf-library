<?php
class HausDesign_Form extends Zend_Form
{
    /**
     * Constructor
     *
     * @param array $options
     */
    function __construct($options = null)
    {
        $this->addPrefixPath('ZendX_JQuery_Form_', 'ZendX/JQuery/Form/');
        $this->addPrefixPath('HausDesign_JQuery_Form_', 'HausDesign/JQuery/Form/');
        $this->addPrefixPath('HausDesign_Form_', 'HausDesign/Form/');
        $this->addPrefixPath('Application_Form_', 'Application/Form/');

        $this->setDecorators(array(
            array('JqueryDescription', array('escape' => false)),
            'JqueryFormErrorMessages',
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),
            'Form'
        ));

        // Set the form name
        $this->setName(strtolower(get_class($this)));

        // Set the method for the display form to POST
        $this->setMethod('post');

        // Call the constructor of the parent object
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
                        array('prefix' => 'HausDesign_', 'path' => 'HausDesign/'),
                        array('prefix' => 'ZendX_', 'path' => 'ZendX/'),
                        array('prefix' => 'Application_', 'path' => 'Application/')
                    );
                } else {
                    $options['prefixPath'][] = array(
                        array('prefix' => 'ZendX_', 'path' => 'ZendX/'),
                        array('prefix' => 'HausDesign_', 'path' => 'HausDesign/'),
                        array('prefix' => 'Application_', 'path' => 'Application/')
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

    public function setSubFormErrorClass()
    {
        foreach ($this->getSubForms() as $subForm) {
            if ($subForm->isErrors()) {
                $class = $subForm->getAttrib('class');

                if (($class !== null ) && ($class != '')) {
                    $class .= ' error';
                } else {
                    $class = 'error';
                }

                $subForm->setAttrib('class', $class);

                $subForm->setSubFormErrorClass();
            }
        }
    }

    public function setRequiredPrefixToElements($requiredPrefix = '* ')
    {
        foreach ($this->getSubForms() as $subForm) {
            $subForm->setRequiredPrefixToElements($requiredPrefix);
        }

        foreach ($this->getElements() as $element) {
            $decorator = $element->getDecorator('Label');
            try {
                if (($decorator !== null) && ($decorator !== false)) {
                    $decorator->setRequiredPrefix($requiredPrefix);
                }
            } catch(Exception $ex) {
                Zend_Debug::dump($ex->getMessage());
            }
        }
    }

    public function setRequiredSuffixToElements($requiredSuffix = ' *')
    {
        foreach ($this->getSubForms() as $subForm) {
            $subForm->setRequiredSuffixToElements($requiredSuffix);
        }

        foreach ($this->getElements() as $element) {
            $decorator = $element->getDecorator('Label');
            try {
                if (($decorator !== null) && ($decorator !== false)) {
                    $decorator->setRequiredSuffix($requiredSuffix);
                }
            } catch(Exception $ex) {
                Zend_Debug::dump($ex->getMessage());
            }
        }
    }
    
    public function checkNull($value)
    {
        if ($value == '') {
            return null;
        } else {
            return $value;
        }
    }
}