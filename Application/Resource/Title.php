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
 * Resource for setting the title in the Zend_Layout object
 *
 * @uses       Zend_Application_Resource_ResourceAbstract
 * @category   HausDesign
 * @package    HausDesign_Application
 * @subpackage Resource
 * @copyright  Copyright (c) 2005-2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class HausDesign_Application_Resource_Title extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Holds the title of the resource
     *
     * @var string
     */
    protected $_title = 'HausDesign CMS';

    /**
     * Holds the separator of the resource
     *
     * @var string
     */
    protected $_separator = ' - ';

    /**
     * Set the title
     *
     * @param  $title string
     * @return Zend_Application_Resource_Title
     */
    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }

    /**
     * Retrieve the saved title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Set the separator for the title
     *
     * @param  $separator string
     * @return Zend_Application_Resource_Title
     */
    public function setSeparator($separator)
    {
        $this->_separator = $separator;
        return $this;
    }

    /**
     * Retrieve the saved separator
     *
     * @return string
     */
    public function getSeparator()
    {
        return $this->_separator;
    }

    /**
     * Defined by Zend_Application_Resource_Resource
     */
    public function init()
    {
        $this->getBootstrap()->bootstrap('view');
        $view = $this->getBootstrap()->getResource('view');

        $view->headTitle($this->getTitle(), 'APPEND')->setSeparator($this->getSeparator());
    }
}
