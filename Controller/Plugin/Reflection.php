<?php
class HausDesign_Controller_Plugin_Reflection extends Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $cache = Zend_Registry::get('cache_manager')->getCache('database');

        if (! $xml = $cache->load('Reflection')) {
            $application = Zend_Controller_Front::getInstance()->getParam('application');

            $paths = array(
                APPLICATION_PATH . DIRECTORY_SEPARATOR . $application
            );

            foreach ($paths as $path) {
                $this->inspectDir($path);
            }

            $cache->save($this->getReflectionXML(), 'Reflection');
        } else {
            $this->getReflectionXML($xml);
        }
    }

    private function inspectDir($path)
    {
        $rdi = new RecursiveDirectoryIterator($path);
        $rii = new RecursiveIteratorIterator($rdi);
        $filtered = new HausDesign_Controller_Plugin_Reflection_Filter($rii);

        iterator_apply($filtered, array($this, 'process'), array($filtered));
    }

    private function process($it = false)
    {
        $this->getReflectionXML()->addItem($it->current());

        return true;
    }
}