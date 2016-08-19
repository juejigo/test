<?php

class Model_Row_SearchCount extends Zend_Db_Table_Row_Abstract 
{
	/**
	 *  插入前
	 * 
	 *  @return void
	 */
	protected function _insert()
	{
		/* 时间戳 */
		
		$this->__set('dateline',SCRIPT_TIME);
		
		/* 最后搜索时间 */
		
		$this->__set('last_time',SCRIPT_TIME);
	}
	
	/**
	 *  更新前
	 * 
	 *  @return void
	 */
	protected function _update()
	{
		/* 最后搜索时间 */
		
		$this->__set('last_time',SCRIPT_TIME);
	}
}

?>