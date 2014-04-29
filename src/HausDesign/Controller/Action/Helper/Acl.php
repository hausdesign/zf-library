<?php
class HausDesign_Controller_Action_Helper_Acl extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * An ACL plugin.
     *
     * @var HausDesign_Controller_Plugin_Acl
     */
    protected $_plugin;

    /**
     * Relays all calls to the ACL plugin.
     *
     * @param   string  $name       The name of the method.
     * @param   array   $arguments  Arguments to pass to the method.
     *
     * @throws  Zend_Controller_Action_Exception    If the ACL helper and plugin don't implement the method.
     *
     * @return  mixed   The return value of the method.
     */
    public function __call($name, array $arguments)
    {
        $callback = array($this->getPlugin(), $name);

        if (is_callable($callback)) {
            return call_user_func_array($callback, $arguments);
        }

        throw new Zend_Controller_Action_Exception(
            'Call to ' . __CLASS__ . '::' . $name . ' could not be handled by ' .
            get_class($this) . ' or ' . get_class($this->getPlugin())
        );
    }

    /**
     * Returns an ACL plugin.
     *
     * @return  HausDesign_Controller_Plugin_Acl
     */
    public function getPlugin()
    {
        if (null === $this->_plugin) {
            $frontController = $this->getFrontController();

            if (! $frontController->hasPlugin('HausDesign_Controller_Plugin_Acl')) {
                $this->_plugin = new HausDesign_Controller_Plugin_Acl();

                $frontController->registerPlugin($this->_plugin);
            } else {
                $this->_plugin = $frontController->getPlugin('HausDesign_Controller_Plugin_Acl');
            }
        }

        return $this->_plugin;
    }
}
