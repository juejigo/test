<?php

class Model_Row_Cart extends Zend_Db_Table_Row_Abstract 
{
	/**
	 *  数据插入前处理
	 * 
	 *  @return void
	 */
	protected function _insert()
	{
		/* 默认选中 */
		
		$this->__set('selected',1);
		
		/* 时间戳 */
		
		$this->__set('dateline',SCRIPT_TIME);
	}
}

?>