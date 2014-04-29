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
 * Holds the custom row object for a versioned table result
 *
 * @category   HausDesign
 * @package    HausDesign_Content
 * @copyright  Copyright (c) 2009 HausDesign (www.HausDesign.nl)
 * @license    http://www.HausDesign.nl/license/new-bsd     New BSD License
 */
class HausDesign_Content_Class_Versioned_Row extends Zend_Db_Table_Row
{
    /**
     * Returns the latest version of a given content class
     *
     * @return  Zend_Db_Table_Row
     * @TODO    Add a parameter which indicates the status (or extra parameters)?
     * @TODO    Limit only the last result, so sort on date/id?
     */
    public function getLatestVersion($select = null)
    {
        $table = $this->getTable()->getVersionTable();

        if ($select === null) {
            $select = $table->select();
        }

        foreach ($this->getTable()->getPrimaryKeys() as $key) {
            $select->where($key .' = ?', $this->_data[$key]);
        }

        $select->limit(1);

        return $table->fetchRow($select);
    }

    /**
     * @TODO    Need a way to get the primary key of the version table
     */
    public function findVersion($version)
    {

    }
}