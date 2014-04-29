<?php
class HausDesign_Filter_Ucfirst implements Zend_Filter_Interface
{
    public function filter($value)
    {
        return ucfirst($value);
    }
}
