<?php

class Model_Row_ProductImage extends Zend_Db_Table_Row_Abstract 
{
	/**
	 *  数据插入前处理
	 * 
	 *  @return void
	 */
	protected function _insert()
	{
		/* 自动设置排序 */
		
		if ($this->_data['product_id'] == 0) 
		{
			$order = $this->_table->getAdapter()->select()
				->from(array('i' => 'product_image'),array(new Zend_Db_Expr('MAX(`order`)')))
				->where('i.member_id = ?',$this->_data['member_id'])
				->where('i.product_id = ?',0)
				->query()
				->fetchColumn();
		}
		else 
		{
			$order = $this->_table->getAdapter()->select()
				->from(array('i' => 'product_image'),array(new Zend_Db_Expr('MAX(`order`)')))
				->where('i.product_id = ?',$this->_data['product_id'])
				->query()
				->fetchColumn();
		}
		
		if (is_null($order)) 
		{
			$this->__set('order',0);
		}
		else 
		{
			$this->__set('order',$order + 1);
		}
		
		/* 状态 */
		
		$this->__set('status','1');
	}
	
	/**
	 *  插入后
	 */
	protected function _postInsert()
	{
		/* 设置主图 */
		
		if (in_array('main',$this->_modifiedFields) && $this->_data['main'] == 1 && $this->_data['main'] != $this->_cleanData['main']) 
		{
			// 删除其他主图
			if ($this->_data['product_id'] == 0) 
			{
				$this->_table->getAdapter()->update('product_image',array('main' => 0),array('id <> ?' => $this->_data['id'],'member_id = ?' => $this->_data['member_id'],'product_id = ?' => $this->_data['product_id']));
			}
			else 
			{
				$this->_table->getAdapter()->update('product_image',array('main' => 0),array('id <> ?' => $this->_data['id'],'product_id = ?' => $this->_data['product_id']));
				$this->_table->getAdapter()->update('product',array('image' => $this->_data['image']),array('id = ?' => $this->_data['product_id']));
			}
		}
	}
	
	/**
	 *  更新后
	 */
	protected function _postUpdate() 
	{
		/* 设置主图 */
		
		if (in_array('main',$this->_modifiedFields) && $this->_data['main'] == 1 && $this->_data['main'] != $this->_cleanData['main']) 
		{
			// 删除其他主图
			if ($this->_data['product_id'] == 0) 
			{
				$this->_table->getAdapter()->update('product_image',array('main' => 0),array('id <> ?' => $this->_data['id'],'member_id = ?' => $this->_data['member_id'],'product_id = ?' => $this->_data['product_id']));
			}
			else 
			{
				$this->_table->getAdapter()->update('product_image',array('main' => 0),array('id <> ?' => $this->_data['id'],'product_id = ?' => $this->_data['product_id']));
				$this->_table->getAdapter()->update('product',array('image' => $this->_data['image']),array('id = ?' => $this->_data['product_id']));
			}
		}
	}
}

?>