<?php

class Model_Row_Scrath extends Zend_Db_Table_Row_Abstract 
{	
	/**
	 *  插入前
	 *
	 *  @return void
	 */
	protected function _insert()
	{
		/* 创建时间 */
	
		$this->__set('add_time',SCRIPT_TIME);
	
	}
}

?>