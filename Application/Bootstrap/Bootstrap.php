<?php
/**
 * HausDesign
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.HausDesign.nl/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@HausDesign.nl so we can send you a copy immediately.
 *
 * @category   HausDesign
 * @package    HausDesign_BootStrap
 * @copyright  Copyright (c) 2009 HausDesign (www.HausDesign.nl)
 * @license    http://www.HausDesign.nl/license/new-bsd     New BSD License
 * @version    $Id: Application.php 229 2009-07-21 22:33:00Z koen $
 */

/**
 * This class bootstraps all the site custom settings for the HausDesign CMS
 * It loads the general.xml file in order to set all the site custom settings
 *
 * @category   HausDesign
 * @package    HausDesign_Application
 * @copyright  Copyright (c) 2009 HausDesign (www.HausDesign.nl)
 * @license    http://www.HausDesign.nl/license/new-bsd     New BSD License
 */
class HausDesign_Application_Bootstrap_Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Custom constructor for the Bootstrap file.
     *
     * It loads another config file in order to allow site-specified config.
     *
     * @param string $application
     */
    public function __construct($application)
    {
         parent::__construct($application);
    }

    /**
     * Intialize the module autoloader in order to allow model loading
     *
     * @access protected
     * @return Zend_Application_Module_Autoloader
     */
    protected function _initAutoload()
    {
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Application',
            'basePath' => APPLICATION_PATH));
        return $moduleLoader;
    }

    public function _initApplication()
    {
        $this->bootstrap('Frontcontroller');
        $frontController = $this->getResource('Frontcontroller');

        $baseUrl = trim($frontController->getBaseUrl(), '/');
        $applicationBaseUrl = trim($this->getApplication()->getBaseUrl(), '/');

        if (is_null($baseUrl)) $baseUrl = '';
        if (is_null($applicationBaseUrl)) $applicationBaseUrl = '';

        if ($applicationBaseUrl != '') {
            if ($baseUrl != '') {
                $baseUrl .= '/';
            }
            $baseUrl .= $applicationBaseUrl;
        }

        if ((! is_null($baseUrl)) && ($baseUrl != '')) {
            $frontController->setBaseUrl('/' . $baseUrl . '/');
        }
    }
}