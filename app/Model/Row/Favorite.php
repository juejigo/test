<?php

class Model_Row_Favorite extends Zend_Db_Table_Row_Abstract 
{
	/**
	 *  数据插入前处理
	 * 
	 *  @return void
	 */
	protected function _insert()
	{
		/* 时间戳 */
		
		$this->__set('dateline',SCRIPT_TIME);
	}
}

?>