<?php

class Model_Row_ProductAddon extends Zend_Db_Table_Row_Abstract 
{
	/**
	 *  插入前
	 * 
	 *  @return void
	 */
	protected function _insert()
	{
		/* 状态 */
		
		$this->__set('status','1');
		
		/* 时间*/
		
		$this->__set('dateline', SCRIPT_TIME);
	}
}
?>