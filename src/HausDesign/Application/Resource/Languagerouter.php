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
 * @version    $Id: Title.php 16200 2009-06-21 18:50:06Z thomas $
 */

/**
 * Resource for setting the language router if necessary
 *
 * @uses       Zend_Application_Resource_ResourceAbstract
 * @category   HausDesign
 * @package    HausDesign_Application
 * @subpackage Resource
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class HausDesign_Application_Resource_Languagerouter extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Defined by Zend_Application_Resource_Resource
     */
    public function init()
    {
        $this->getBootstrap()->bootstrap('locale');
        $locale = Zend_Registry::get('Zend_Locale');

        $route = new Zend_Controller_Router_Route(
            ':language/:module/:controller/:action/*',
            array(
                'language'   => $locale->getLanguage(),
                'module'     => 'default',
                'controller' => 'index',
                'action'     => 'index'
            ),
            array('language' => '[a-z]{2}')
        );

        $this->getBootstrap()->bootstrap('frontController');
        $router = $this->getBootstrap()->getResource('frontController')->getRouter();

        $router->addRoute('language', $route);

        $route = new Zend_Controller_Router_Route(
            ':language/:controller/:action/*',
            array(
                'language'   => $locale->getLanguage(),
                'module'     => 'default',
                'controller' => 'index',
                'action'     => 'index'
            ),
            array('language' => '[a-z]{2}')
        );

        $router->addRoute('language_nomodule', $route);
    }
}
