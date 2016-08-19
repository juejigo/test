<?php

class Model_Row_VoteRecord extends Zend_Db_Table_Row_Abstract 
{
    /**
     *  插入前
     *
     *  @return void
     */
    protected function _insert()
    {
        /* 创建时间 */
    
        $this->__set('dateline',SCRIPT_TIME);
    
    }
}

?>