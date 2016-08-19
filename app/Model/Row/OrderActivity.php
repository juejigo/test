<?php

class Model_Row_OrderActivity extends Zend_Db_Table_Row_Abstract 
{
	/**
	 *  数据插入前处理
	 * 
	 *  @return void
	 */
	protected function _insert()
	{
		/* 时间 */
		
		$this->__set('dateline',SCRIPT_TIME);
	}
}

?>