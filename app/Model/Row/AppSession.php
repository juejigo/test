<?php

class Model_Row_AppSession extends Zend_Db_Table_Row_Abstract 
{
	/**
	 *  数据插入前处理
	 * 
	 *  @return void
	 */
	protected function _insert()
	{
		/* 自动生成 id */
		if (empty($this->_data['id'])) 
		{
			$this->__set('id',$this->_getTable()->autoId());
		}
		
		/* 更新时间 */
		$this->__set('last_update',SCRIPT_TIME);
		
		/* 状态 */
		$this->__set('status',1);
	}
}

?>