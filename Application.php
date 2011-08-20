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
 * @package    HausDesign_Application
 * @copyright  Copyright (c) 2009 HausDesign (www.HausDesign.nl)
 * @license    http://www.HausDesign.nl/license/new-bsd     New BSD License
 * @version    $Id: Application.php 229 2009-07-21 22:33:00Z koen $
 */

require_once 'Zend/Application.php';

 /**
 * Initializes the HausDesign CMS
 *
 * @category   HausDesign
 * @package    HausDesign_Application
 * @copyright  Copyright (c) 2009 HausDesign (www.HausDesign.nl)
 * @license    http://www.HausDesign.nl/license/new-bsd     New BSD License
 */
class HausDesign_Application extends Zend_Application
{
    /**
     * Holds the application of the current request
     *
     * @var string
     */
    protected $_application = null;

    /**
     * Holds the base url
     *
     * @var string
     */
    protected $_baseUrl = null;

    /**
     * Get the application name
     *
     * @return string
     */
    public function getApplication()
    {
        if (is_null($this->_application)) {
            $this->_application = $this->_parseApplicationFromUrl();
        }

        return $this->_application;
    }

    /**
     * Get the base url
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->_baseUrl;
    }

    /**
     * Constructor
     *
     * Initialize application. Potentially initializes include_paths, PHP
     * settings, and bootstrap class.
     *
     * It also loads the default config file for the HausDesign CMS.
     *
     * @param  string                   $environment
     * @param  string|array|Zend_Config $options String path to configuration file, or array/Zend_Config
     *                                  of configuration options
     * @throws Zend_Application_Exception When invalid options are provided
     * @throws HausDesign_Application_Exception When invalid general options are provided
     * @return void
     */
    public function __construct($environment, $options = null)
    {
        parent::__construct($environment, $options);

        // Retrieve the current application
        $this->_application = $this->_parseApplicationFromUrl();

        define('CUR_APPLICATION_PATH', realpath(APPLICATION_PATH . '' . DIRECTORY_SEPARATOR . '' . $this->_application . '' . DIRECTORY_SEPARATOR));

        // Get the application specific file
        // Normally located at /application/*application name*/configs/application.ini
        $applicationOptions = array();
        $applicationConfigFile = CUR_APPLICATION_PATH . '' . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'application.ini';
        if (file_exists($applicationConfigFile)) {
            $applicationOptions = $this->_loadConfig($applicationConfigFile);
        }

        // Merge the options and force them into Zend_Application
        $this->setOptions($this->mergeOptions($this->getOptions(), $applicationOptions));

        // Add the options to the Zend Registry
        Zend_Registry::set('config', $this->getOptions());
    }

    /**
     * Returns the Application Name of the current request
     *
     * @return string
     * @todo Allow wildcard for the subdomains
     * @todo Error handling
     */
    protected function _parseApplicationFromUrl()
    {
        $application = 'application';

        if ($this->_application == null) {
            try {
                $config = new Zend_Config_Xml(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'domains.xml');

                if (isset($config->default) && isset($config->default->application)) {
                    $application = $config->default->application;
                }

                $cleanDomainName = $_SERVER['HTTP_HOST'];
                $subdomain = '';

                $httpHostParts = explode('.', $_SERVER['HTTP_HOST']);

                switch (count($httpHostParts)) {
                	case '1':
                		$cleanDomainName = $httpHostParts[0];
                		$subdomain = '';
                		break;

                	case '2':
                		$cleanDomainName = $httpHostParts[count($httpHostParts) - 2] . '.' . $httpHostParts[count($httpHostParts) - 1];
                		$subdomain = $httpHostParts[0];
                		break;

                	case '3':
                		$cleanDomainName = $httpHostParts[count($httpHostParts) - 2] . '.' . $httpHostParts[count($httpHostParts) - 1];
                		$subdomain = $httpHostParts[0];
                		break;
                }

                // Check if there is a domain configuration available for the entered domain
                $basePath = '';

                $configDomain = null;
                foreach ($config->domains as $domain) {
                    foreach($domain->addresses as $address) {
                        if (($address->address == $cleanDomainName) || ($address->address == '*')) {
                            $configDomain = $domain;
                            $basePath = $address->base;

                            $url = preg_replace('#^' . trim($basePath, '/') . '#', '', trim($_SERVER['REQUEST_URI'], '/'));

                			$requestUriParts = explode('/', trim($url, '/'));
                			$directory = $requestUriParts[0];
                        }
                    }
                }

                // Execute the domain configuration (routing)
                if (! is_null($configDomain)) {
                    $application = $configDomain->default;
                    if (($configDomain->applications !== null) && (is_object($configDomain->applications)) && (get_class($configDomain->applications) == 'Zend_Config')) {
                        foreach ($configDomain->applications as $configApplicationName => $configApplication) {
                            switch ($configApplication->type) {
                                case 'subdomain':
                                    if ($subdomain == $configApplicationName) {
                                        $application = $configApplication->application;
                                        break;
                                    }
                                    break;
                                case 'directory':
                                    if ($directory == $configApplicationName) {
                                        $application = $configApplication->application;
                                        if ($basePath != '') $basePath .= '/';
                                        $this->_baseUrl = $basePath . $application;
                                        break;
                                    }
                                    break;
                                case 'both':
                                    if ($directory == $configApplicationName) {
                                    	$application = $configApplication->application;
                                        //if ($basePath != '') $basePath .= '/';
                                        $this->_baseUrl = $basePath . $application;
                                    	break;
                                    } elseif ($subdomain == $configApplicationName) {
                                        $application = $configApplication->application;
                                        break;
                                    }
                                    break;
                            }
                        }
                    }
                } else {
                    
                }
            } catch (Exception $exception) {
                
            }

            $this->_application = $application;

            if (($this->_baseUrl === null) && ($basePath != '')) {
                $this->_baseUrl = $basePath;
            }

            return $this->_application;

            //$_SERVER['REQUEST_URI'] = '/' . implode($pathSegments, '/') . '/';
        }
     }
}