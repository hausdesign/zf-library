<?php
class HausDesign_Controller_Action extends Zend_Controller_Action
{
    /**
     * Module name
     * 
     * @var string
     */
    protected $_moduleName;

    /**
     * Controller name
     * 
     * @var string
     */
    protected $_controllerName;

    /**
     * Action name
     * 
     * @var string
     */
    protected $_actionName;

    /**
     * Translation name
     * 
     * @var string
     */
    protected $_translationName;

    /**
     * Log
     *  
     * @var Zend_Log
     */
    protected $_log;

    /**
     * FlashMessenger
     *
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    protected $_flashMessenger = null;

    /**
     * (non-PHPdoc)
     * @see Zend_Controller_Action::init()
     */    
    public function init()
    {
        parent::init();

        // Load the log
        $this->_log = $this->getInvokeArg('bootstrap')->log;

        // Load the flashmessenger
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->messages = $this->_flashMessenger->getMessages();

        // Load the module, controller and action names
        $this->view->module_name = $this->_moduleName = ucfirst($this->getRequest()->getModuleName());
        $this->view->controller_name = $this->_controllerName = ucfirst($this->getRequest()->getControllerName());
        $this->view->action_name = $this->_actionName = ucfirst($this->getRequest()->getActionName());

        // Load the translation name
        $this->view->translation_name = $this->_translationName = $this->_moduleName . $this->_controllerName . $this->_actionName;
    }
}