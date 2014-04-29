<?php
class HausDesign_Datatable
{
    protected $_data;
    protected $_totalCount;
    protected $_filteredCount;

    function __construct(Zend_Controller_Request_Abstract $request, array $orderFields, array $searchFields, Zend_Db_Table_Select $select, Zend_Db_Table_Select $selectFiltered)
    {
        // Load the params
        $iDisplayStart = $request->getParam('iDisplayStart');
        $iDisplayLength = $request->getParam('iDisplayLength');
        $iSortingCols = intval($request->getParam('iSortingCols'));
        $sSearch = $request->getParam('sSearch');
        
        // Build sort array
        $order = array();
        if ($iSortingCols > 0) {
            for ($i = 0; $i < $iSortingCols; $i++) {
                if (array_key_exists($request->getParam('iSortCol_'. $i), $orderFields)) {
                    $order[] = $orderFields[$request->getParam('iSortCol_'. $i)] . ' ' . strtoupper($request->getParam('sSortDir_'. $i));
                }
            }
        }

        // Count the total rows
        $selectCount = clone $select;
        if (count($selectCount->getPart(Zend_Db_Table_Select::GROUP)) <= 0) {
            $selectCount->reset(Zend_Db_Table_Select::COLUMNS)->columns(array('count' => 'COUNT(*)'));
            $this->_totalCount = $selectCount->getTable()->fetchRow($selectCount)->count;
        } else {
            $this->_totalCount = $selectCount->getTable()->fetchAll($selectCount)->count();
        }

        // Append search
        if (($sSearch !== null) && ($sSearch != '')) {
            $sSearch = '\'%' . $sSearch . '%\'';
            $selectFiltered->where('(' . implode(' LIKE ' . $sSearch . ') OR (', $searchFields) . ' LIKE ' . $sSearch . ')');
        }

        // Count the filtered rows
        $selectCount = clone $selectFiltered;
        if (count($selectCount->getPart(Zend_Db_Table_Select::GROUP)) <= 0) {
            $selectCount->reset(Zend_Db_Table_Select::COLUMNS)->columns(array('count' => 'COUNT(*)'));
            $this->_filteredCount = $selectCount->getTable()->fetchRow($selectCount)->count;
        } else {
            $this->_filteredCount = $selectCount->getTable()->fetchAll($selectCount)->count();
        }

        // Load the limited result
        $selectFiltered->limit($iDisplayLength, $iDisplayStart);
        if (count($order) > 0) {
            $selectFiltered->order($order);
        }

        $this->_data = $selectFiltered->getTable()->fetchAll($selectFiltered);
    }

    /**
     * Get total count
     * 
     * @return int
     */
    public function getTotalCount()
    {
        return $this->_totalCount;
    }

    /**
     * Get filtered count
     * 
     * @return int
     */
    public function getFilteredCount()
    {
        return $this->_filteredCount;
    }

    /**
     * Get data
     * 
     * @return Zend_Db_Table_Rowset
     */
    public function getData()
    {
        return $this->_data;
    }
}