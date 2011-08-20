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
 * @package    HausDesign_Content
 * @copyright  Copyright (c) 2009 HausDesign (www.HausDesign.nl)
 * @license    http://www.HausDesign.nl/license/new-bsd     New BSD License
 * @version    $Id: Application.php 229 2009-07-21 22:33:00Z koen $
 */

/**
 * Implements a Versioned Content Class of the HausDesign CMS.
 *
 * This object overrides the default hausdesign content class and extends it in such way that
 * a custom row is being used which has support for selecting version
 *
 * @category   HausDesign
 * @package    HausDesign_Content
 * @copyright  Copyright (c) 2009 HausDesign (www.HausDesign.nl)
 * @license    http://www.HausDesign.nl/license/new-bsd     New BSD License
 */
abstract class HausDesign_Content_Class_Versioned extends HausDesign_Content_Class
{
    /**
     * This variable holds the reference to the version table of this content class
     * @var Zend_Db_Table
     */
    protected $_versionedTable;

    /**
     * This function extends the default init of the parent class, it allows us to load
     * the necessary row class objects and others.
     */
    public function init()
    {
        $this->setRowClass('HausDesign_Content_Class_Versioned_Row');
    }

    /**
     * This function returns the object to the version table of this content class
     *
     * @return  Zend_Db_Table
     */
    public function getVersionTable()
    {
        return $this->_versionedTable;
    }

    /**
     * This function sets the object to the version table of this content class
     *
     * @param   Zend_Db_Table $table    Holds the object to the version table
     */
    public function setVersionTable($table)
    {
        if (! ($table instanceof Zend_Db_Table_Abstract)) {
            throw new HausDesign_Content_Exception("Version table needs to be of type Zend_Db_Table");
        }

        $this->_versionedTable = $table;
    }

    /**
     * This function returns an array with the primary key names
     *
     * @return  array
     */
    public function getPrimaryKeys()
    {
        return (array) $this->_primary;
    }
}