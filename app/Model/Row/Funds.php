<?php

class Model_Row_Funds extends Zend_Db_Table_Row_Abstract 
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
	
	/**
	 *  更新后
	 */
	protected function _postUpdate() 
	{
		/* 确认提成 */
	    
	    if(in_array($this->_data['type'],array(1,2)) && in_array('status',$this->_modifiedFields) && $this->_cleanData['status'] == 0 && $this->_data['status'] == 1)
	    {
	    	$money = abs($this->_data['money']);
			$this->_table->getAdapter()->update('member',array('balance' => new Zend_Db_Expr("balance + {$money}")),array('id = ?' => $this->_data['member_id']));
	    }
		
		/* 提现驳回 */
		
	    if($this->_data['type'] == 0 && in_array('status',$this->_modifiedFields) && $this->_cleanData['status'] == 0 && $this->_data['status'] == -1)
	    {
	    	$money = abs($this->_data['money']); 
			$this->_table->getAdapter()->update('member',array('balance' => new Zend_Db_Expr("balance + {$money}")),array('id = ?' => $this->_data['member_id']));
	    }
	}
}

?>