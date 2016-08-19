<?php

class Model_Row_ProductTag extends Zend_Db_Table_Row_Abstract 
{
	/**
	 *  插入前
	 * 
	 *  @return void
	 */
	protected function _insert()
	{
		/* 状态 */
		
		$this->__set('status','1');
	}
	
	/**
	 *  更新后
	 */
	protected function _postUpdate() 
	{
		/* 删除标签数据 */
		
		if ($this->_data['status'] == -1) 
		{
			$this->_table->getAdapter()->delete('product_tagdata',array('tag_id = ?' => $this->_data['id']));
		}
	}
	
	/**
	 *  删除后
	 */
	protected function _postDelete() 
	{
		/* 删除标签数据 */
		
		if ($this->_data['parent_id'] > 0) 
		{
			$this->_table->getAdapter()->delete('product_tagdata',array('tag_id = ?' => $this->_data['id']));
		}
	}
}

?>