<?php
require_once 'Zend/Application.php';

class HausDesign_Application2 extends Zend_Application
{
    /**
     * Holds the application
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

        $this->_parseUrl();

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

    protected function _parseUrl()
    {
        // Get the http host
        $httpHost = $_SERVER['HTTP_HOST'];

        // Split http host into chunks
        $httpHostChunks = explode('.', $_SERVER['HTTP_HOST']);

        // Is there a subdomein (first element in http host) specified?
        $subdomain = '';
        if (count($httpHostChunks) == 1) {
            $domain = $httpHostChunks[0];
        } else {
            $subdomain = array_shift($httpHostChunks);
            $domain = implode('.', $httpHostChunks);
        }

        // Get querystring
        $queryString = trim($_SERVER['REQUEST_URI'], '/');

        // Split querystring into chunks
        $queryStringChunks = explode('/', $queryString);

        // Is there a directory (first element in query string) specified?
        $directory = '';
        if (isset($queryStringChunks[0])) {
            $directory = (string) $queryStringChunks[0];
        }

        $filepath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'routing.xml';
        if (file_exists($filepath)) {
            $xml = simplexml_load_file($filepath);
            foreach ($xml as $route) {
                $check = true;

                if ($check && ((string) $route['domain'] != '') && ((string) $route['domain'] != '*')) {
                    if ($domain != (string) $route['domain']) {
                        $check = false;
                    }
                }

                if ($check && ((string) $route['subdomain'] != '') && ((string) $route['subdomain'] != '*')) {
                    if ($subdomain != (string) $route['subdomain']) {
                        $check = false;
                    }
                }

                if ($check && ((string) $route['directory'] != '') && ((string) $route['directory'] != '*')) {
                    if ($directory != (string) $route['directory']) {
                        $check = false;
                    }
                }

                if ($check) {
                    $this->_application = (string) $route->application;
                    $this->_baseUrl = (string) $route->baseurl;
                    break;
                }
            }
        }
    }
}