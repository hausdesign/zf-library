<?php
class HausDesign_Form_Crud extends HausDesign_Form
{
    /**
     * Buttons
     * 
     * @var array
     */
    protected $_buttons;

    /**
     * Subforms
     * 
     * @var HausDesign_Form_SubForm
     */
    protected $_subFormSubForms;

    /**
     * Constructor
     *
     * @param array $options
     */
    function __construct($options = null)
    {
        if (($options !== null) && (array_key_exists('buttons', $options))) {
            $this->_buttons = $options['buttons'];
            unset($options['buttons']);
        } else {
            // Load the view object
            $view = $this->getView();

            // Add the default buttons
            $this->_buttons = array(
                //'delete' => array(
                //    'label' => ucfirst($view->translate('delete')),
                //    'group' => 'buttons_left'
                //),
                'save' => array(
                    'label' => ucfirst($view->translate('save')),
                    'group' => 'buttons_right'
                ),
                'cancel' => array(
                    'label' => ucfirst($view->translate('cancel')),
                    'group' => 'buttons_right'
                )
            );
        }

        parent::__construct($options);
    }

    public function init()
    {
        parent::init();

        // Load the view object
        $view = $this->getView();

        // Append the javascript files
        $view->headScript()->appendFile($view->baseUrl('/scripts/hausdesign/js/jquery.once.js', false));
        $view->headScript()->appendFile($view->baseUrl('/scripts/hausdesign/js/hausdesign.js', false));
        $view->headScript()->appendFile($view->baseUrl('/scripts/hausdesign/js/vertical-tabs.js', false));
        $view->headScript()->appendFile($view->baseUrl('/scripts/hausdesign/js/collapse.js', false));

        // Create the subform (tabs)
        $this->_subFormSubForms = new HausDesign_Form_SubForm(array('name' => 'subForms', 'isArray' => false));
        $this->_subFormSubForms->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'div', 'class' => 'vertical-tabs-panes'))
        ));

        // Call the function for initializing the elements
        $this->_initializeElements();

        // Add the subform (tabs)
        $this->addSubForm($this->_subFormSubForms, 'SubForms');

        // Set the decorators for the subforms (tabs)
        $this->_subFormSubForms->setSubFormDecorators(array(
            'Description',
            'FormElements',
            array('Fieldset') //, array('class' => 'menu-link-form form-wrapper collapsible'))
        ));

        // Add the buttons to the form
        $this->addButtons($this->_buttons);

        // Add a hidden element to the form with the form name
        if (($this->getName() !== null) && ($this->getName() != '')) {
            $this->addElement('hidden', 'form_name', array('value' => $this->getName(), 'decorators' => array('ViewHelper')));
        }

        // Set the name of the form as form class
        $this->addElement('hidden', 'form_class', array('value' => get_class($this), 'decorators' => array('ViewHelper'), 'ignore' => true));

        // Set the required suffix to all form elements
        $this->setRequiredSuffixToElements(' *');
    }

    /**
     * Initialize the elements
     *
     */
    protected function _initializeElements()
    {
        
    }

    /**
     * Add buttons
     * 
     * @var array $buttons
     */
    public function addButtons($buttons)
    {
        $displayGroups = array();

        foreach ($buttons as $buttonName => $button) {
            $this->addElement('submit', $buttonName, array('label' => $button['label'], 'decorators' => array('ViewHelper', 'Errors'), 'attribs' => array('class' => 'button')));

            if ((array_key_exists('group', $button)) && ($button['group'] !== null) && ($button['group'] != '')) {
                if (! array_key_exists($button['group'], $displayGroups)) {
                    $displayGroups[$button['group']] = array();
                }

                $displayGroups[$button['group']][] = $buttonName;
            }
        }

        // Loop the available displaygroups and add them to the form with the 
        // selected elements
        foreach ($displayGroups as $displayGroupName => $displayGroupElements) {
            // Add the displaygroup for the selected elements
            $this->addDisplayGroup($displayGroupElements, $displayGroupName);
        }
    }
}