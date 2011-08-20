<?php
class HausDesign_Application_Resource_Logging extends Zend_Application_Resource_ResourceAbstract
{
    public function init()
    {
        $request = new Zend_Controller_Request_Http();
        if (! $request->isXmlHttpRequest()) {
            $options = $this->getOptions();
            if (isset($options['url']) && ($options['url'] !== null) && ($options['url'] != '')) {
                try {
                    try {
                        if (($application = dirname($_SERVER['DOCUMENT_ROOT'])) !== null) {
                            $applicationDirectories = split(DIRECTORY_SEPARATOR, $application);
                            $application = array_pop($applicationDirectories);
                        }
                    } catch (Exception $exception) {
                        $application = '';
                    }

                    $client = new Zend_Http_Client($options['url'], array('maxredirects' => 0, 'timeout' => 3));
                    $client->setParameterPost(array(
                        'application' => $application,
                        'host' => gethostname(),
                        'data' => $_SERVER,
                        'message' => 'Page request',
                        'ipaddress' => $this->_getIP(),
                    ))->request('POST');
                } catch (Exception $exception) {
                    
                }
            }
        }
    }

    protected function _getIP()
    {
        $variables = array(
            'HTTP_CLIENT_IP', 
            'HTTP_X_FORWARDED_FOR', 
            'HTTP_X_FORWARDED', 
            'HTTP_X_CLUSTER_CLIENT_IP', 
            'HTTP_FORWARDED_FOR', 
            'HTTP_FORWARDED', 
            'REMOTE_ADDR'
        );

        foreach ($variables as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }
}