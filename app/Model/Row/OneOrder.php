<?php

class Model_Row_OneOrder extends Zend_Db_Table_Row_Abstract
{
	/**
	 *  数据插入前处理
	 *
	 *  @return void
	 */
	protected function _insert()
	{
		/* 订单号 */
		
		if (empty($this->_data['id']))
		{
			$this->__set('id',$this->_table->createId());
		}
		
		/* 未付款 设置5分钟自动关闭 */
		
		$this->__set('clock',SCRIPT_TIME + (5*60));
		
		/* 时间戳 */
		
		$this->__set('dateline',SCRIPT_TIME);

		/* 状态 */
		if (empty($this->_data['status']))
		{
			$this->__set('status',0);
		}
	}
	
	/**
	 *  更新后
	 */
	protected function _update()
	{
		/* 状态 */
		if (in_array('status',$this->_modifiedFields) && $this->_data['status'] != $this->_cleanData['status'] && $this->_data['status'] == 1)
		{
			$this->__set('pay_time',SCRIPT_TIME);
		}
	}
	
	/**
	 *  更新后
	 */
	protected function _postUpdate()
	{
		if (in_array('status',$this->_modifiedFields) && $this->_data['status'] != $this->_cleanData['status'] && $this->_data['status'] == 3)
		{
			
		}
	}
}

?>