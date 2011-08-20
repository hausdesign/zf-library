<?php
class HausDesign_Form_Element_Wysiwyg extends Zend_Form_Element_Textarea
{
    /**
     * Use formTextarea view helper by default
     * @var string
     */
    public $helper = 'formWysiwyg';

    /**
     * Base url
     * 
     * @var string
     */
    public $baseUrl;

    /**
     * Base directory
     * 
     * @var string
     */
    public $baseDir;

    /**
     * Set the base url
     * 
     * @param string $value
     */
    public function setBaseUrl($value)
    {
    	$this->baseUrl = $value;
    }

    /**
     * Get the base url
     * 
     * @return string
     */
    public function getBaseUrl()
    {
    	return $this->baseUrl;
    }

    /**
     * Set the base directory
     * 
     * @param string $value
     */
    public function setBaseDir($value)
    {
    	$this->baseDir = $value;
    }

    /**
     * Get the base directory
     * 
     * @return string
     */
    public function getBaseDir()
    {
    	return $this->baseDir;
    }
}