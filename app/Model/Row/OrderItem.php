<?php

class Model_Row_OrderItem extends Zend_Db_Table_Row_Abstract 
{
	/**
	 *  数据插入前处理
	 * 
	 *  @return void
	 */
	protected function _insert()
	{
		/* 状态 */
		
		$this->__set('status',0);
	}
	
	/**
	 *  更新前
	 */
	protected function _update() 
	{
		/* 支付 */
		
		if (in_array('status',$this->_modifiedFields) && $this->_cleanData['status'] == 0 && $this->_data['status'] == 1) 
		{
			// 设置有效使用次数
			$this->__set('available_num',$this->_data['num']);
		}
	}
}

?>