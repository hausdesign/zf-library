<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   HausDesign
 * @package    HausDesign_Application
 * @subpackage Resource
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Db.php 16200 2009-06-21 18:50:06Z thomas $
 */

/**
 * Resource for adding the default autoloaders to each module in the application
 *
 * @uses       Zend_Application_Resource_Frontcontroller
 * @category   HausDesign
 * @package    HausDesign_Application
 * @subpackage Resource
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class HausDesign_Application_Resource_Frontcontroller extends Zend_Application_Resource_Frontcontroller
{
    /**
     * Load configuration file of options
     *
     * @return Zend_Controller_Front
     */
    public function init()
    {
        $front = parent::init();

        $application = $this->getBootstrap()->getApplication();

        if (get_class($application) == 'HausDesign_Application') {
            $front->setParam('application', $application->getApplication());
        }

        // $loaders = array();
        // foreach ($this->getFrontController()->getControllerDirectory() as $module => $directory) {
        //     $loaders[$module] = new Zend_Application_Module_Autoloader(array(
        //         'namespace' => ucfirst($module),
        //         'basePath'  => ucfirst(dirname($directory)),
        //     ));
        // }

        return $front;
    }
}