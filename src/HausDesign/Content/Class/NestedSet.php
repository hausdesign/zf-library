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
abstract class HausDesign_Content_Class_NestedSet extends HausDesign_Content_Class
{
    /**
     * Holds the name of the database column indicating the left value of a node
     *
     * @var string
     */
    protected $_left = 'lft';

    /**
     * Holds the name of the database column indicating the right value of a node
     *
     * @var string
     */
    protected $_right = 'rgt';

    /**
     * This function extends the default init of the parent class, it allows us to load
     * the necessary row class objects and others.
     */
    public function init()
    {
        $this->setRowClass('HausDesign_Content_Class_NestedSet_Row');
    }

    /**
     * Returns the tree by an given id, if no id is given the root will be taken
     * @param   int
     * @return  Zend_Db_Table_RowSet
     * @TODO    Change query to Zend_Db_Select
     */
    public function getTree($id=null)
    {
        $where = '';
        if ($id !== null) {
            $row = call_user_func_array(array($this, 'find'), $id);
            $where = " node.".$this->_db->quoteIdentifier($this->_left)." >= ".$this->_db->quote($row[0]->{$this->_left})." AND
                node.".$this->_db->quoteIdentifier($this->_right) ." <= ".$this->_db->quote($row[0]->{$this->_right})." AND ";
        }

        $primaryKeys = (array) $this->_primary;

        foreach ($primaryKeys as $i => $el) {
            $primaryKeys[$i] = 'node.'.$this->_db->quoteIdentifier($el);
        }

        $ret = $this->_db->query("
            SELECT      COUNT(parent.".$this->_db->quoteIdentifier($this->_left).") - 1 AS depth,
                        node.*
            FROM        ".$this->_db->quoteIdentifier($this->_name)." AS node,
                        ".$this->_db->quoteIdentifier($this->_name)." AS parent
            WHERE       ".$where."node.".$this->_db->quoteIdentifier($this->_left)."
                        BETWEEN parent.".$this->_db->quoteIdentifier($this->_left)." AND
                                parent.".$this->_db->quoteIdentifier($this->_right)."
            GROUP BY    ".implode(', ', $primaryKeys)."
            ORDER BY    node.".$this->_db->quoteIdentifier($this->_left)." 
        ");
         
        return $ret->fetchAll();
    }

    public function getNestedTree($id = null)
    {
        $nodes = $this->getTree($id);

        // Trees mapped
        $trees = array();
        $l = 0;

        if (count($nodes) > 0) {
            // Node Stack. Used to help building the hierarchy
            $stack = array();

            foreach ($nodes as $node) {
                $item = $node;
                $item['children'] = array();

                // Number of stack items
                $l = count($stack);

                // Check if we're dealing with different levels
                while ($l > 0 && $stack[$l - 1]['depth'] >= $item['depth']) {
                    array_pop($stack);
                    $l--;
                }

                // Stack is empty (we are inspecting the root)
                if ($l == 0) {
                    // Assigning the root node
                    $i = count($trees);
                    $trees[$i] = $item;
                    $stack[] = & $trees[$i];
                } else {
                    // Add node to parent
                    $i = count($stack[$l - 1]['children']);
                    $stack[$l - 1]['children'][$i] = $item;
                    $stack[] = & $stack[$l - 1]['children'][$i];
                }
            }
        }

        return $trees;
    }

    /**
     * This function deletes a node by a given ID and returns the number of affected rows
     *
     * @param   int $id     Holds the ID of the node to delete
     *
     * @return  int
     *
     * @TODO    Transaction support?
     */
    public function deleteNode($id)
    {
        $row = $this->_getNode($id);

        $left = $row[0]->{$this->_left};
        $right = $row[0]->{$this->_right};
        $width = $right - $left + 1;

        $res = $this->delete($this->_db->quoteInto($this->_left . ' >= ?', $left) . ' AND ' . $this->_db->quoteInto($this->_right . ' <= ?', $right));

        $this->update(array($this->_right => new Zend_Db_Expr('`' . $this->_right . '` = `'. $this->_right . '` - '.$width)), $this->_db->quoteInto($this->_right . ' > ?', $right));
        $this->update(array($this->_left => new Zend_Db_Expr('`' . $this->_left . '` = `'. $this->_left . '` - '.$width)), $this->_db->quoteInto($this->_right . ' > ?', $right));

        return $res->rowCount();
    }

    /**
     * Inserts a new item as the first child of the given id
     *
     * @param   mixed $id   Holds the id of the node
     * @param   array $data Holds the data to insert
     *
     * @return  int
     *
     * @TODO    Transaction support?
     * @TODO    Change queries to Zend_Db_Select?
     */
    public function insertAsFirstChildOf($id, array $data)
    {
        $row = $this->_getNode($id);

        $right = (int) $row[0]->{$this->_right};
        $left  = (int) $row[0]->{$this->_left};

        $this->_db->query("UPDATE {$this->_name} SET {$this->_right} = {$this->_right} + 2 WHERE {$this->_right} > {$left}");
        $this->_db->query("UPDATE {$this->_name} SET {$this->_left} = {$this->_left} + 2 WHERE {$this->_left} > {$left}");

        $data[$this->_left]  = $left + 1;
        $data[$this->_right] = $left + 2;

        return $this->insert($data);
    }

    /**
     * Inserts a new item as the last child of the given id
     *
     * @param   mixed $id   Holds the id of the node
     * @param   array $data Holds the data to insert
     *
     * @return  int
     *
     * @TODO    Transaction support?
     * @TODO    Change queries to Zend_Db_Select?
     */
    public function insertAsLastChildOf($id, array $data)
    {
        $row = $this->_getNode($id); //call_user_func_array(array($this, 'find'), $id);

        $right = (int) $row[0]->{$this->_right};
        $left  = (int) $row[0]->{$this->_left};

        $this->_db->query("UPDATE {$this->_name} SET {$this->_right} = {$this->_right} + 2 WHERE {$this->_right} >= {$right}");
        $this->_db->query("UPDATE {$this->_name} SET {$this->_left} = {$this->_left} + 2 WHERE {$this->_left} > {$right}");

        $data[$this->_left]  = $right;
        $data[$this->_right] = $right + 1;

        return $this->insert($data);
    }

    /**
     * Inserts the node as the next sibling of a given id.
     *
     * @param   mixed $id   Holds the id of the node
     * @param   array $data Holds the data to insert
     *
     * @throws  HausDesign_Content_Class_NestedSet_Exception    When the given id is the root
     *
     * @return  int
     *
     * @TODO    Transaction support?
     * @TODO    Change queries to Zend_Db_Select?
     */
    public function insertAsNextSiblingOf($id, array $data)
    {
        $row = $this->_getNode($id);

        $right = (int) $row[0]->{$this->_right};
        $left  = (int) $row[0]->{$this->_left};

        if ($left === 1) {
            throw new HausDesign_Content_Class_NestedSet_Exception("Root node can't have siblings.");
        }

        $this->_db->query("UPDATE {$this->_name} SET {$this->_right} = {$this->_right} + 2 WHERE {$this->_right} > {$right}");
        $this->_db->query("UPDATE {$this->_name} SET {$this->_left} = {$this->_left} + 2 WHERE {$this->_left} > {$right}");

        $data[$this->_left]  = $right + 1;
        $data[$this->_right] = $right + 2;

        return $this->insert($data);
    }

    /**
     * Inserts the node as the previous sibling of a given id.
     *
     * @param   mixed $id   Holds the id of the node
     * @param   array $data Holds the data to insert
     *
     * @throws  HausDesign_Content_Class_NestedSet_Exception    When the given id is the root
     *
     * @return  int
     *
     * @TODO    Transaction support?
     * @TODO    Change queries to Zend_Db_Select?
     * @TODO    Check for not the same nodes
     */
    public function insertAsPrevSiblingOf($id, array $data)
    {
        $row = $this->_getNode($id);

        $right = (int) $row[0]->{$this->_right};
        $left  = (int) $row[0]->{$this->_left};

        if ($left === 1) {
            throw new HausDesign_Content_Class_NestedSet_Exception("Root node can't have siblings.");
        }

        $this->_db->query("UPDATE {$this->_name} SET {$this->_right} = {$this->_right} + 2 WHERE {$this->_right} > {$left}");
        $this->_db->query("UPDATE {$this->_name} SET {$this->_left} = {$this->_left} + 2 WHERE {$this->_left} >= {$left}");

        $data[$this->_left]  = $left;
        $data[$this->_right] = $left + 1;

        return $this->insert($data);
    }

    /**
     * Insert the root node
     *
     * @param   array $data Holds the data for the root node
     *
     * @return  int
     */
    public function createRoot(array $data)
    {
        $data[$this->_left]  = 1;
        $data[$this->_right] = 2;

        return $this->insert($data);
    }

    /**
     * Returns the node by a given id, if null is given the root will be returned
     *
     * @param   mixed $id Holds the id to fetch
     *
     * @return  Zend_Db_RowSet
     *
     * @TODO    Error checking
     */
    protected function _getNode($id=null)
    {
        if ($id !== null) {
            return call_user_func_array(array($this, 'find'), $id);
        } else {
            return $this->fetchAll($this->select()->where($this->_db->quoteIdentifier($this->_left) . ' = 1'));
        }
    }
}

class HausDesign_Content_Class_NestedSet_Exception extends HausDesign_Exception {}