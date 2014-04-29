<?php
class HausDesign_Upload
{
    protected $allowedExtensions = array();
    protected $sizeLimit = 10485760;
    protected $file;

    protected $_fileName;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760)
    {
        $allowedExtensions = array_map("strtolower", $allowedExtensions);

        $this->allowedExtensions = $allowedExtensions;
        $this->sizeLimit = $sizeLimit;

        $this->checkServerSettings();

        if (isset($_GET['qqfile'])) {
            $this->file = new HausDesign_Upload_Xhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new HausDesign_Upload_Form();
        } else {
            $this->file = false;
        }
    }

    private function checkServerSettings()
    {
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit) {
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");
        }
    }

    private function toBytes($str)
    {
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    /**
     * Returns array('success' => true) or array('error' => 'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = false)
    {
        if (!is_writable($uploadDirectory)) {
            return array('error' => "Server error. Upload directory isn't writable.");
        }

        if (!$this->file) {
            return array('error' => 'No files were uploaded.');
        }

        $size = $this->file->getSize();

        if ($size == 0) {
            return array('error' => 'File is empty');
        }

        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large');
        }

        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];
        //$filename = md5(uniqid());
        $ext = $pathinfo['extension'];

        if ($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)) {
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }

        $filenameSuffix = '';
        $this->_fileName = $filename . $filenameSuffix . '.' . $ext;

        // Don't overwrite previous files that were uploaded
        if (!$replaceOldFile) {
            $i = 0;
            while (file_exists($uploadDirectory . $this->_fileName)) {
                $i++;
                $filenameSuffix = '_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                $this->_fileName = $filename . $filenameSuffix . '.' . $ext;
            }
        }

        if ($this->file->save($uploadDirectory . $this->_fileName)) {
            return array('success' => true);
        } else {
            return array('error'=> 'Could not save uploaded file. The upload was cancelled, or server error encountered');
        }
    }
    
    public function getFileName()
    {
        return $this->_fileName;
    }
}