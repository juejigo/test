<?php

class Model_Row_NewsCate extends Zend_Db_Table_Row_Abstract 
{
	/**
	 *  插入前
	 * 
	 *  @return void
	 */
	protected function _insert()
	{
		/* 层级 */
		
		if ($this->_data['parent_id'] > 0) 
		{
			$parentLevel = $this->_table->getAdapter()->select()
				->from(array('c' => 'news_cate'),array('level'))
				->where('c.id = ?',$this->_data['parent_id'])
				->query()
				->fetchColumn();
			$this->__set('level',new Zend_Db_Expr("{$parentLevel} + 1"));
		}
		else 
		{
			$this->__set('level',1);
		}
		
		/* 时间戳 */
		$this->__set('dateline',SCRIPT_TIME);
		
		/* 状态 */
		$this->__set('status','1');
		
		/* 默认可删*/
		$this->__set('allow_delete',1);
	}
	
	/**
	 *  插入后
	 */
	protected function _postInsert() 
	{
		if ($this->_data['parent_id'] > 0) 
		{
			$this->_table->getAdapter()->update('news_cate',array('children' => new Zend_Db_Expr('children + 1')),array('id = ?' => $this->_data['parent_id']));
		}
	}
	
	/**
	 *  更新后
	 */
	protected function _postUpdate() 
	{
		if (in_array('status',$this->_modifiedFields) && $this->_data['status'] == -1 && $this->_data['parent_id'] > 0) 
		{
			$this->_table->getAdapter()->update('news_cate',array('children' => new Zend_Db_Expr('children - 1')),array('id = ?' => $this->_data['parent_id']));
		}
	}
	
	/**
	 *  删除后
	 */
	protected function _postDelete() 
	{
		if ($this->_data['parent_id'] > 0) 
		{
			$this->_table->getAdapter()->update('news_cate',array('children' => new Zend_Db_Expr('children - 1')),array('id = ?' => $this->_data['parent_id']));
		}
	}
}

?>