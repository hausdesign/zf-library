<?php 
class HausDesign_Log extends Zend_Log
{
    /**
     * Log filename (including the complete path)
     * 
     * @var string
     */
    protected $_filename;

    /**
     * Start/creation time
     * 
     * @var string
     */
    protected $_startTime;

    public function __construct($logFilename = null, $description = null, $logDirectory = null)
    {
        // If no directory entered, user the default log folder
        if ($logDirectory === null) {
            $logDirectory = realpath(PUBLIC_PATH . DIRECTORY_SEPARATOR .'..' . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;
        } else {
            $logDirectory = realpath($logDirectory) . DIRECTORY_SEPARATOR;
        }

        if ($logDirectory !== false) {
            // If no filename entered, create a new name
            $this->_filename = $logFilename;
            if ($logFilename === null) {
                $this->_filename = 'log';
            }

            $currentDate = new Zend_Date();

            // Get the microtime
            $microtime = microtime();
            $microtime = str_replace('.', '', $microtime);
            $microtime = str_replace(' ', '', $microtime);

            // Prepend the current date and time to the filename 
            $this->_filename = $currentDate->toString('yyyy-MM-dd-HH-mm-ss') . '_' . $microtime . '_' . $this->_filename;

            // Build the complete filename (including the complete path)
            $this->_filename = $logDirectory . $this->_filename . '.txt';

            // If the log file allready exists, try to create a unique filename by
            // appending a number to the filename
            $i = 1;
            while ((is_file($this->_filename)) && ($i < 100)) {
                $this->_filename = $logDirectory . '_' . $i . '.txt'; 
                $i++;
            }

            // If it is not possible to create a unique filename, throw an error
            if (is_file($this->_filename)) {
                throw new Zend_Controller_Action_Exception('Cannot create the log file. The log file allready exists.');
            }

            // Add a stream writer to the log object
            $logWriter = new Zend_Log_Writer_Stream($this->_filename);

            // Construct the parent log object
            parent::__construct($logWriter);

            // Set the timestamp format in the log
            $this->setTimestampFormat('Y-m-d H:i:s');

            // Calculate the start time
            $mtime = microtime();
            $mtime = explode(' ', $mtime);
            $mtime = $mtime[1] + $mtime[0];
            $this->_startTime = $mtime;

            $currentDate = new Zend_Date();

            // Write the import header to the log
            $this->log('------------------------------------', 6);
            if ($description !== null) {
                $this->log(' Description: ' . $description, 6);
            }
            $this->log(' Log File: ' . $this->_filename, 6);
            $this->log(' Start: ' . $currentDate->toString('yyyy-MM-dd HH:mm:ss:S'), 6);
            $this->log('------------------------------------', 6);
        }
    }

    /**
     * Write a footer to the log including the end time and the duration
     * 
     * @return HausDesign_Log
     */
    public function close()
    {
        // Calculate the end time
        $mtime = microtime();
        $mtime = explode(' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        // Get the duration of the import
        $totaltime = ($endtime - $this->_startTime); 

        $currentDate = new Zend_Date();

        // Write the footer to the log
        $this->log('------------------------------------', 6);
        $this->log(' End: ' . $currentDate->toString('yyyy-MM-dd HH:mm:ss:S'), 6);
        $this->log(' Elapsed: ' . $totaltime . ' seconds', 6);
        $this->log('------------------------------------', 6);

        // Return
        return $this;
    }
    
    /**
     * Get the log filename (including the complete path)
     * 
     * @return string
     */
    public function getFilename()
    {
        return $this->_filename;
    }

    /**
     * Get the content of the log file
     * 
     * @return string
     */
    public function getContent()
    {
       if (file_exists($this->getFilename())) {
           return file_get_contents($this->getFilename());
       }
    }

    /**
     * Delete the log file
     * 
     * @return bool
     */
    public function deleteFile()
    {
       if (file_exists($this->getFilename())) {
           unlink($this->getFilename());
       }
    }
}