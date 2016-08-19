<?php

class Model_Row_OneLuckyNum extends Zend_Db_Table_Row_Abstract
{
	/**
	 *  数据插入前处理
	 *
	 *  @return void
	 */
	protected function _insert()
	{
		/* 是否中奖*/
	
		$this->__set('is_win',0);
	}
	
	/**
	 *  更新后
	 */
	protected function _postUpdate()
	{
		//改变订单状态
		if (in_array('is_win',$this->_modifiedFields) && $this->_data['is_win'] != $this->_cleanData['is_win'] && $this->_data['is_win'] == 1)
		{
			$model = new Model_OneOrder();
			$row = $model->fetchRow(
				$model->select()
					->where('id = ?',$this->_data['order_id'])
			);
			if (!empty($row))
			{
				$row->status = 2;
				$row->save();
			}
			
		}
	}
}

?>