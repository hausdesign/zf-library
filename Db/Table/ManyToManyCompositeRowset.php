<?php
/**
 * HausDesign_Db_Table_ManyToManyCompositeRowset
 *
 * Distributed under the same BSD License as Zend Framework (http://framework.zend.com)
 *
 * @category HausDesign
 * @package HausDesign_Db
 * @copyright Copyright (c) 2005-2010 Ralph Schindler
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

/**
 * ManyToManyCompositeRowset
 *
 * @author ralphschindler
 */
class HausDesign_Db_Table_ManyToManyCompositeRowset implements SeekableIterator, ArrayAccess, Countable
{
    /**
     * @var int
     */
    protected $_count = null;

    /**
     * @var int
     */
    protected $_pointer = 0;

    /**
     * @var Zend_Db_Table_Rowset_Abstract
     */
    protected $_matchRowset = null;

    /**
     * @var Zend_Db_Table_Rowset_Abstract
     */
    protected $_junctionRowset = null;

    /**
     * @var int
     */
    protected $_junctionRowsetIndex = array();

    /**
     * keys: columms, refTableClass, refColumns
     *
     * @var array
     */
    protected $_referenceMap = array();

    /**
     * Constructor
     *
     * @param Zend_Db_Table_Row_Abstract $row
     * @param string|Zend_Db_Table_Abstract $matchTableName
     * @param string|Zend_Db_Table_Abstract $junctionTableName
     * @param string $matchRefRule
     * @throws Zend_Db_Table_Rowset_Exception
     */
    public function __construct(Zend_Db_Table_Row_Abstract $row, $matchTableName, $junctionTableName, $matchRefRule = null)
    {
        $this->_matchRowset = $row->findManyToManyRowset($matchTableName, $junctionTableName);
        $this->_junctionRowset = $row->findDependentRowset($junctionTableName);
        if (count($this->_matchRowset) != count($this->_junctionRowset)) {
            throw new Zend_Db_Table_Rowset_Exception('Mismatch in values returned in the matching rowset and the junction rowset');
        }

        // set count
        $this->_count = count($this->_matchRowset);

        // prepare junction rowset index (to ensure order)
        $junctionData = $this->_junctionRowset->toArray(); // will get raw data, no iteration

        // get name of column to key off of
        /* @var $t Zend_Db_Table */
        $junctionTable = new $junctionTableName();
        $this->_referenceMap = $junctionTable->getReference($matchTableName, $matchRefRule);

        foreach ($junctionData as $index => $data) {
            $this->_junctionRowsetIndex[$index] = $data[$this->_referenceMap['columns'][0]]; // @todo: identify use case with compound key
        }
    }

    /**
     * Take the Iterator to position $position
     * Required by interface SeekableIterator.
     *
     * @param int $position the position to seek to
     * @return Zend_Db_Table_Rowset_Abstract
     * @throws Zend_Db_Table_Rowset_Exception
     */
    public function seek($position)
    {
        $position = (int) $position;
        $this->_matchRowset->seek($position);
        $this->_pointer = $position;
        return $this;
    }

    /**
     * Return the current element.
     *
     * Similar to the current() function for arrays in PHP
     * Required by interface Iterator.
     *
     * @return Zend_Db_Table_Row_Abstract current element from the collection
     */
    public function current()
    {
        return $this->_matchRowset->current();
    }

    /**
     * Return the current junction row.
    * 
     * Similar to the current() function for arrays in PHP
     * Required by interface Iterator.
     *
     * @return Zend_Db_Table_Row_Abstract current element from the collection
     */
    public function currentJunction()
    {
        // the following block ensures that the order returned by the junction look does not matter
        $curMatch = $this->current();
        $value = $curMatch[$this->_referenceMap['refColumns'][0]]; // @todo: compound keys
        $index = array_search($value, $this->_junctionRowsetIndex);
        $this->_junctionRowset->seek($index);

        return $this->_junctionRowset->current();
    }
    
    /**
     * Move forward to next element.
     * Similar to the next() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return void
     */
    public function next()
    {
        ++$this->_pointer;
        $this->_matchRowset->next();
    }

    /**
     * Rewind the Iterator to the first element.
     * Similar to the reset() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return Zend_Db_Table_Rowset_Abstract Fluent interface.
     */
    public function rewind()
    {
        $this->_pointer = 0;
        $this->_matchRowset->rewind();
        return $this;
    }

    /**
     * Return the identifying key of the current element.
     * Similar to the key() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return int
     */
    public function key()
    {
        return $this->_pointer;
    }
    
    /**
     * Check if there is a current element after calls to rewind() or next().
     * Used to check if we've iterated to the end of the collection.
     * Required by interface Iterator.
     *
     * @return bool False if there's nothing more to iterate over
     */
    public function valid()
    {
        return $this->_matchRowset->valid();
    }

    /**
     * Does nothing (usually)
     * Required by the ArrayAccess implementation
     *
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        return $this->_matchRowset->offsetSet($offset, $value);
    }

    /**
     * Get the row for the given offset
     * Required by the ArrayAccess implementation
     *
     * @param string $offset
     * @return Zend_Db_Table_Row_Abstract
     */
    public function offsetGet($offset)
    {
        return $this->_matchRowset->offsetGet($offset);
    }

    /**
     * Check if an offset exists
     * Required by the ArrayAccess implementation
     *
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return $this->_matchRowset->offsetExists($offset);
    }

    /**
     * Does nothing (usually)
     * Required by the ArrayAccess implementation
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        return $this->_matchRowset->offsetUnset($offset);
    }

    /**
     * Returns the number of elements in the collection.
     *
     * Implements Countable::count()
     *
     * @return int
     */
    public function count()
    {
        return count($this->_matchRowset);
    }

    /**
     * Returns a Zend_Db_Table_Row from a known position into the Iterator
     *
     * @param int $position the position of the row expected
     * @param bool $seek wether or not seek the iterator to that position after
     * @return Zend_Db_Table_Row
     * @throws Zend_Db_Table_Rowset_Exception
     */
    public function getRow($position, $seek = false)
    {
        return $this->_matchRowset->getRow($position, $seek);
    }

    /**
     * Returns a Zend_Db_Table_Row from a known position into the Iterator
     *
     * @param int $position the position of the row expected
     * @param bool $seek wether or not seek the iterator to that position after
     * @return Zend_Db_Table_Row
     * @throws Zend_Db_Table_Rowset_Exception
     */
    public function getJunctionRow($position, $seek = false)
    {
        return $this->_junctionRowset->getRow($position, $seek);
    }

    /**
     * Returns all data as an array.
     *
     * Updates the $_data property with current row object values.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->_matchRowset->toArray();
    }

    /**
     * Returns all junction table data as an array.
     *
     * Updates the $_data property with current row object values.
     *
     * @return array
     */
    public function junctionRowsetToArray()
    {
        return $this->_junctionRowset->toArray();
    }
}