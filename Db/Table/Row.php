<?php
class HausDesign_Db_Table_Row extends Zend_Db_Table_Row
{
    public function connect()
    {
        if (! $this->isConnected()) {
            $table = $this->getTableClass();
            $this->setTable(new $table());
        }
    }

    /**
     * Retrieves an associative array of primary keys.
     *
     * @param bool $useDirty
     * @return array
     */
    public function getPrimaryKey($useDirty = true)
    {
        return parent::_getPrimaryKey($useDirty);
    }

    public function findManyToManyRowsetComplete($matchTable, $intersectionTable, $callerRefRule = null,
                                         $matchRefRule = null, Zend_Db_Table_Select $select = null)
    {
        $db = $this->_getTable()->getAdapter();

        if (is_string($intersectionTable)) {
            $intersectionTable = $this->_getTableFromString($intersectionTable);
        }

        if (!$intersectionTable instanceof Zend_Db_Table_Abstract) {
            $type = gettype($intersectionTable);
            if ($type == 'object') {
                $type = get_class($intersectionTable);
            }
            require_once 'Zend/Db/Table/Row/Exception.php';
            throw new Zend_Db_Table_Row_Exception("Intersection table must be a Zend_Db_Table_Abstract, but it is $type");
        }

        // even if we are interacting between a table defined in a class and a
        // table via extension, ensure to persist the definition
        if (($tableDefinition = $this->_table->getDefinition()) !== null
            && ($intersectionTable->getDefinition() == null)) {
            $intersectionTable->setOptions(array(Zend_Db_Table_Abstract::DEFINITION => $tableDefinition));
        }

        if (is_string($matchTable)) {
            $matchTable = $this->_getTableFromString($matchTable);
        }

        if (! $matchTable instanceof Zend_Db_Table_Abstract) {
            $type = gettype($matchTable);
            if ($type == 'object') {
                $type = get_class($matchTable);
            }
            require_once 'Zend/Db/Table/Row/Exception.php';
            throw new Zend_Db_Table_Row_Exception("Match table must be a Zend_Db_Table_Abstract, but it is $type");
        }

        // even if we are interacting between a table defined in a class and a
        // table via extension, ensure to persist the definition
        if (($tableDefinition = $this->_table->getDefinition()) !== null
            && ($matchTable->getDefinition() == null)) {
            $matchTable->setOptions(array(Zend_Db_Table_Abstract::DEFINITION => $tableDefinition));
        }

        if ($select === null) {
            $select = $matchTable->select();
        } else {
            $select->setTable($matchTable);
        }

        // Use adapter from intersection table to ensure correct query construction
        $interInfo = $intersectionTable->info();
        $interDb   = $intersectionTable->getAdapter();
        $interName = $interInfo['name'];
        $interSchema = isset($interInfo['schema']) ? $interInfo['schema'] : null;
        $matchInfo = $matchTable->info();
        $matchName = $matchInfo['name'];
        $matchSchema = isset($matchInfo['schema']) ? $matchInfo['schema'] : null;

        $matchMap = $this->_prepareReference($intersectionTable, $matchTable, $matchRefRule);

        for ($i = 0; $i < count($matchMap[Zend_Db_Table_Abstract::COLUMNS]); ++$i) {
            $interCol = $interDb->quoteIdentifier('i' . '.' . $matchMap[Zend_Db_Table_Abstract::COLUMNS][$i], true);
            $matchCol = $interDb->quoteIdentifier('m' . '.' . $matchMap[Zend_Db_Table_Abstract::REF_COLUMNS][$i], true);
            $joinCond[] = "$interCol = $matchCol";
        }
        $joinCond = implode(' AND ', $joinCond);

        $select->from(array('i' => $interName), Zend_Db_Select::SQL_WILDCARD, $interSchema)
               ->joinInner(array('m' => $matchName), $joinCond, Zend_Db_Select::SQL_WILDCARD, $matchSchema)
               ->setIntegrityCheck(false);

        $callerMap = $this->_prepareReference($intersectionTable, $this->_getTable(), $callerRefRule);

        for ($i = 0; $i < count($callerMap[Zend_Db_Table_Abstract::COLUMNS]); ++$i) {
            $callerColumnName = $db->foldCase($callerMap[Zend_Db_Table_Abstract::REF_COLUMNS][$i]);
            $value = $this->_data[$callerColumnName];
            $interColumnName = $interDb->foldCase($callerMap[Zend_Db_Table_Abstract::COLUMNS][$i]);
            $interCol = $interDb->quoteIdentifier("i.$interColumnName", true);
            $interInfo = $intersectionTable->info();
            $type = $interInfo[Zend_Db_Table_Abstract::METADATA][$interColumnName]['DATA_TYPE'];
            $select->where($interDb->quoteInto("$interCol = ?", $value, $type));
        }

        $stmt = $select->query();

        $config = array(
            'table'    => $matchTable,
            'data'     => $stmt->fetchAll(Zend_Db::FETCH_ASSOC),
            'rowClass' => $matchTable->getRowClass(),
            'readOnly' => false,
            'stored'   => true
        );

        $rowsetClass = $matchTable->getRowsetClass();
        if (!class_exists($rowsetClass)) {
            try {
                require_once 'Zend/Loader.php';
                Zend_Loader::loadClass($rowsetClass);
            } catch (Zend_Exception $e) {
                require_once 'Zend/Db/Table/Row/Exception.php';
                throw new Zend_Db_Table_Row_Exception($e->getMessage(), $e->getCode(), $e);
            }
        }
        $rowset = new $rowsetClass($config);
        return $rowset;
    }
}