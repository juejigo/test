<?php

class Model_Row_Product extends Zend_Db_Table_Row_Abstract 
{
	/**
	 *  插入前
	 * 
	 *  @return void
	 */
	protected function _insert()
	{
		/* 创建时间 */
		
		$this->__set('dateline',SCRIPT_TIME);
		
		
		/* 状态 */
		
		$this->__set('status',0);
		
		/* 排序*/
		
		$this->__set('order',1);
		
		/* 排序*/
		
		$this->_table->getAdapter()->update('product',array('order' => new Zend_Db_Expr("`order`+1")));
	}
	
	/**
	 *  插入后
	 */
	public function _postInsert()
	{
		/* 更新图片 */
		
		//$this->_table->getAdapter()->update('product_image',array('product_id' => $this->_data['id']),array('product_id = ?' => 0,'member_id = ?' => Zend_Auth::getInstance()->getIdentity()->id));	
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
				$this->_table->getAdapter()->update('product',array('order' => new Zend_Db_Expr("`order`-1")),array('`order` <= ?' => $this->_data['order'],'`order` > ?' => $this->_cleanData['order']));
			}
			else
			{
				$this->_table->getAdapter()->update('product',array('order' =>  new Zend_Db_Expr("`order`+1")),array('`order` >= ?' => $this->_data['order'],'`order` < ?' => $this->_cleanData['order']));
			}
		}
		
		if (in_array('up_time', $this->_modifiedFields) && $this->_data['up_time'] != $this->_cleanData['up_time'] && $this->_data['status'] == 3 && $this->_data['parent_id'] == 0 && $this->_data['up_time'] > SCRIPT_TIME && $this->_data['down_time'] > SCRIPT_TIME)
		{
			$this->__set('status',0);
		}
	}
	
	/**
	 *  更新后
	 */
	protected function _postUpdate() 
	{	
    	//出行日期改变
    	if (in_array('status',$this->_modifiedFields) && $this->_data['status'] != $this->_cleanData['status'] && $this->_data['parent_id'] != 0)
    	{
    		$travelDate = $this->_table->getAdapter()->select()
	    		->from(array('p' => 'product'),array(new Zend_Db_Expr('MIN(travel_date)')))
	    		->where('p.parent_id = ?',$this->_data['parent_id'])
	    		->where('travel_date > ?',SCRIPT_TIME)
	    		->where('status in (?)',array(0,2))
	    		->query()
	    		->fetchColumn();
	    		
    		if (!empty($travelDate))
    		{
    			$this->_table->getAdapter()->update('product',array('travel_date' => $travelDate),array('id = ?' => $this->_data['parent_id']));
    		}
    	}
	}
}

?>