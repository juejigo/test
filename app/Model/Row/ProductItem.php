<?php

class Model_Row_ProductItem extends Zend_Db_Table_Row_Abstract 
{
	/**
	 *  数据插入前处理
	 * 
	 *  @return void
	 */
	protected function _insert()
	{
		/* 状态 */
		$this->__set('status','1');
	}
}

?>