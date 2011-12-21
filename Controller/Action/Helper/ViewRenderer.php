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
 * @package    HausDesign_Controller
 * @subpackage Action_Helper
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Db.php 16200 2009-06-21 18:50:06Z thomas $
 */

/**
 * Change the ViewRenderer to support view overruling in the templates directory
 *
 * @uses       Zend_Controller_Action_Helper_ViewRenderer
 * @category   HausDesign
 * @package    HausDesign_Controller
 * @subpackage Action_Helper
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class HausDesign_Controller_Action_Helper_ViewRenderer extends Zend_Controller_Action_Helper_ViewRenderer
{
    /**
     * This function updates the ViewRender helper to add another view directory
     * which is located in the templates directory.
     *
     * This adds the feature to override view script in certain templates.
     *
     * @param  string $script
     * @param  string $name
     * @return void
     */
    public function renderScript($script, $name = null)
    {
        $application = $this->getFrontController()->getParam('application');
        $module = $this->getModule();
        $scriptPath = $this->view->layout()->getViewScriptPath() . 'views' . DIRECTORY_SEPARATOR . $application . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR;
        if (! in_array($scriptPath, $this->view->getScriptPaths())) {
            $this->view->addScriptPath($scriptPath);
        }

        parent::renderScript($script, $name);
    }
}