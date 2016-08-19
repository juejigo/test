<?php

class Model_Row_PositionData extends Zend_Db_Table_Row_Abstract 
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
		
		/* 时间戳 */
		
		$this->__set('dateline',SCRIPT_TIME);
		
		/* 序号*/
		
		$this->__set('order', 1);
		
		/* 排序*/
		
		$this->_table->getAdapter()->update('position_data',array('order' => new Zend_Db_Expr("`order`+1")),array('position_id = ?' => $this->_data['position_id']));
	}
	
	/**
	 *  更新前
	 */
	public function _update()
	{
		if (in_array('order', $this->_modifiedFields) && $this->_data['order'] != $this->_cleanData['order'])
		{
			if ($this->_data['order'] > $this->_cleanData['order'])
			{
				$this->_table->getAdapter()->update('position_data',array('order' => new Zend_Db_Expr("`order`-1")),array('`order` <= ?' => $this->_data['order'],'`order` > ?' => $this->_cleanData['order']));
			}
			else
			{
				$this->_table->getAdapter()->update('position_data',array('order' =>  new Zend_Db_Expr("`order`+1")),array('`order` >= ?' => $this->_data['order'],'`order` < ?' => $this->_cleanData['order']));
			}
		}
	}
}

?>