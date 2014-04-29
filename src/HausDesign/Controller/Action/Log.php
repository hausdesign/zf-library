<?php
class HausDesign_Controller_Action_Log extends HausDesign_Controller_Action
{
    /**
     * Zend_Log object
     *
     * @var Zend_Log
     */
    protected $_log = null;

    /**
     * Enter description here...
     *
     * @var string
     */
    protected $_logFilename = null;

    /**
     * Script start time
     *
     * @var datetime
     */
    protected $_startTime;

    /**
     * Enter description here...
     *
     */
    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
    	parent::__construct($request, $response, $invokeArgs);
    }

    /**
     * Initialize the log
     *
     * @param string $filename
     */
    protected function _initLog($filename = null)
    {
   		if (is_null($filename)) {
        	$filename = str_replace('/', '_', __FILE__);
        }

   		$basePath = realpath(PUBLIC_PATH . '/../') . '/' . 'temp/logs' . '/';
        $logFilename = $basePath . date('Y-m-d_H-i-s') . '_' . $filename . '.txt';

        $i = 1;
        while ((is_file($logFilename)) && ($i < 10)) {
			$logFilename = $basePath . '/' . date('Y-m-d_H-i-s') . '_' . $filename . '_' . $i . '.txt'; 
        	$i++;
        }

        if (is_file($logFilename)) {
        	throw new Zend_Controller_Action_Exception('Cannot create the log file. The log file allready exists.');
        }

        $this->_logFilename = $logFilename;
        $this->_log = fopen($logFilename, 'w');

        $mtime = microtime();
        $mtime = explode(' ',$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $this->_startTime = $mtime;

        $this->_log('------------------------------------', 6);
        $this->_log('  Hostname: ' . 'localhost', 6);
        $this->_log('    Script: ' . __FILE__, 6);
        $this->_log('  Log File: ' . $this->_logFilename, 6);
        $this->_log('     Start: ' . date('Y-m-d H:i:s'), 6);
        $this->_log('------------------------------------', 6);
    }

    /**
     * Close the log
     *
     */
    protected function _closeLog()
    {
    	if (! is_null($this->_log)) {
	        $this->_log('------------------------------------', 6);
	        $this->_log('      End: ' . date('Y-m-d H:i:s'), 6);

	        // Calculate the end time
	        $mtime = microtime();
	        $mtime = explode(' ', $mtime);
	        $mtime = $mtime[1] + $mtime[0];
	        $endtime = $mtime;
	        $totaltime = ($endtime - $this->_startTime); 

	        $this->_log('  Elapsed: ' . $totaltime . ' seconds', 6);
	        $this->_log('------------------------------------', 6);

	        @fclose($this->_logFilename);
    	} else {
    		throw new Zend_Controller_Action_Exception('The log is not initialized yet');
    	}
    }

    /**
     * Log a value to the global log object and print the value on the screen
     *
     * @param variant $value
     * @param int $priority
     * @param bool $echo
     */
    protected function _log($value, $priority = 6, $echo = false)
    {
        //$this->_log->log($value, $priority);
        //if ($echo) {
        //	Zend_Debug::dump($value);
        //	flush();
        //	ob_flush();
        //}
        fwrite($this->_log, date('r') .  ' - ' . $value . "\r\n");
        //var_dump($value);
    }
}