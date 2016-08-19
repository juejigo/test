<?php

class Model_Row_OneAddress extends Zend_Db_Table_Row_Abstract
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

		/* 状态 */
		
		$this->__set('status','1');
		
		/* 默认*/
		
		$this->__set('default',0);
	}
}

?>