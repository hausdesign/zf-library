<?php
class HausDesign_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    /**
     * Zend_Auth object
     *
     * @var Zend_Auth
     */
    protected $_auth;

    /**
     * Whitelist
     * 
     * @var array
     */
    protected $_whitelist = array();

    /**
     * The error handler module.
     *
     * @var string
     */
    protected $_storage;

    /**
     * The error handler module.
     *
     * @var string
     */
    protected $_errorModule;

    /**
     * The error handler controller.
     *
     * @var string
     */
    protected $_errorController;

    /**
     * The error handler action.
     *
     * @var string
     */
    protected $_errorAction;

    /**
     * Creates an Auth plugin.
     *
     * @param   mixed   $options    An array of options.
     */
    public function __construct($options = array())
    {
        $this->setOptions($options);
    }

    /**
     * Sets the storage and error handler options.
     *
     * @param   array   $options    An array of options.
     *
     * @return  HausDesign_Controller_Plugin_Auth
     */
    public function setOptions(array $options = array())
    {
        $validOptions = array(
            'whitelist',
            'storage',
            'errorHandlerModule',
            'errorHandlerController',
            'errorHandlerAction',
        );

        foreach ($validOptions as $option) {
            if (array_key_exists($option, $options)) {
                $this->{'set' . $option}($options[$option]);
            }
        }

        return $this;
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        if (($request->getParam('sid') !== null) && ($request->getParam('PHPSESSID') === null)) {
            $request->setParam('PHPSESSID', $request->getParam('sid'));
        }

        if ($request->getParam('PHPSESSID') === null) {
            $module = strtolower($request->getModuleName());
            $controller = strtolower($request->getControllerName());
            $action = strtolower($request->getActionName());

            $route = $module . '/' . $controller . '/' . $action;

            if (! in_array($route, $this->_whitelist)) {
                if (is_null($this->_auth)) {
                    $auth = Zend_Auth::getInstance();
                    $auth->setStorage(new Zend_Auth_Storage_Session($this->getStorage()));
                    $this->_auth = $auth;
                }

                if (! $this->_auth->hasIdentity()) {
                    $errorHandler = new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);
                    $errorHandler->type      = 'EXCEPTION_NOT_ALLOWED';
                    $errorHandler->exception = new Zend_Controller_Action_Exception('No credentials available');
                    $errorHandler->request   = clone $request;

                    $request->setParam('error_handler', $errorHandler)
                            ->setModuleName($this->getErrorHandlerModule())
                            ->setControllerName($this->getErrorHandlerController())
                            ->setActionName($this->getErrorHandlerAction());
                }
            }
        }
    }

    /**
     * Sets the whitelist
     *
     * @param   array  The whitelist.
     *
     * @return  HausDesign_Controller_Plugin_Acl
     */
    public function setWhitelist($whitelist)
    {
        $this->_whitelist = $whitelist;

        return $this;
    }

    /**
     * Returns the whitelist
     *
     * @return  string  The whitelist.
     */
    public function getWhitelist()
    {
        return $this->_whitelist;
    }

    /**
     * Sets the storage
     *
     * @param   string  $module The storage of Zend_Auth.
     *
     * @return  HausDesign_Controller_Plugin_Acl
     */
    public function setStorage($storage)
    {
        $this->_storage = (string) $storage;

        return $this;
    }

    /**
     * Returns the storage of Zend_Auth.
     *
     * @return  string  The storage of Zend_Auth.
     */
    public function getStorage()
    {
        if (is_null($this->_storage) || $this->_storage == '') {
            $this->_storage = 'Zend_Auth';
        }

        return $this->_storage;
    }

    /**
     * Sets the error handler module.
     *
     * @param   string  $module The module to forward to when an error occurs.
     *
     * @return  HausDesign_Controller_Plugin_Acl
     */
    public function setErrorHandlerModule($module)
    {
        $this->_errorModule = (string) $module;

        return $this;
    }

    /**
     * Returns the error handler module, defaulting to the Zend_Controller_Plugin_ErrorHandler plugin
     * or the dispatcher's default module.
     *
     * @return  string  The module to forward to when an error occurs.
     */
    public function getErrorHandlerModule()
    {
        if (null === $this->_errorModule) {
            $frontController = Zend_Controller_Front::getInstance();

            if ($errorHandler = $frontController->getPlugin('Zend_Controller_Plugin_ErrorHandler')) {
                $this->_errorModule = $errorHandler->getErrorHandlerModule();
            } else {
                $this->_errorModule = $frontController->getDispatcher()->getDefaultModule();
            }
        }

        return $this->_errorModule;
    }

    /**
     * Sets the error handler controller.
     *
     * @param   string  $controller The controller to forward to when an error occurs.
     *
     * @return  HausDesign_Controller_Plugin_Acl
     */
    public function setErrorHandlerController($controller)
    {
        $this->_errorController = (string) $controller;

        return $this;
    }

    /**
     * Returns the error handler controller, defaulting to the Zend_Controller_Plugin_ErrorHandler plugin..
     *
     * @return  string  The controller to forward to when an error occurs.
     */
    public function getErrorHandlerController()
    {
        if (null === $this->_errorController) {
            $frontController = Zend_Controller_Front::getInstance();

            if ($errorHandler = $frontController->getPlugin('Zend_Controller_Plugin_ErrorHandler')) {
                $this->_errorController = $errorHandler->getErrorHandlerController();
            } else {
                $this->_errorController = 'error';
            }
        }

        return $this->_errorController;
    }

    /**
     * Sets the error handler action.
     *
     * @param   string  $action The action to forward to when an error occurs.
     *
     * @return  HausDesign_Controller_Plugin_Acl
     */
    public function setErrorHandlerAction($action)
    {
        $this->_errorAction = (string) $action;

        return $this;
    }

    /**
     * Returns the error handler action, defaulting to the Zend_Controller_Plugin_ErrorHandler plugin..
     *
     * @return  string  The action to forward to when an error occurs.
     */
    public function getErrorHandlerAction()
    {
        if (null === $this->_errorAction) {
            $frontController = Zend_Controller_Front::getInstance();

            if ($errorHandler = $frontController->getPlugin('Zend_Controller_Plugin_ErrorHandler')) {
                $this->_errorAction = $errorHandler->getErrorHandlerAction();
            } else {
                $this->_errorAction = 'error';
            }
        }

        return $this->_errorAction;
    }
}