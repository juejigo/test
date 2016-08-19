<?php

class Model_Row_ProductCate extends Zend_Db_Table_Row_Abstract 
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
				->from(array('c' => 'product_cate'),array('level'))
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
			$this->_table->getAdapter()->update('product_cate',array('children' => new Zend_Db_Expr('children + 1')),array('id = ?' => $this->_data['parent_id']));
		}
	}
	
	/**
	 *  更新前
	 */
	protected function _update()
	{
		/* 层级 */
		
		if ($this->_data['parent_id'] > 0) 
		{
			$parentLevel = $this->_table->getAdapter()->select()
				->from(array('c' => 'product_cate'),array('level'))
				->where('c.id = ?',$this->_data['parent_id'])
				->query()
				->fetchColumn();
			$this->__set('level',new Zend_Db_Expr("{$parentLevel} + 1"));
		}
		else 
		{
			$this->__set('level',1);
		}
	}
	
	/**
	 *  更新后
	 */
	protected function _postUpdate() 
	{
		if (in_array('status',$this->_modifiedFields) && $this->_data['status'] == -1 && $this->_data['parent_id'] > 0) 
		{
			$this->_table->getAdapter()->update('product_cate',array('children' => new Zend_Db_Expr('children - 1')),array('id = ?' => $this->_data['parent_id']));
		}
	}
	
	/**
	 *  删除后
	 */
	protected function _postDelete() 
	{
		if ($this->_data['parent_id'] > 0) 
		{
			$this->_table->getAdapter()->update('product_cate',array('children' => new Zend_Db_Expr('children - 1')),array('id = ?' => $this->_data['parent_id']));
		}
	}
	
	/**
	 *  是否有直接子分类
	 * 
	 *  @return Zend_Db_Table_Row_Abstract|null
	 */
	public function hasChildren()
	{
		return $this->_table->select()
			->from($this->_table,array(new Zend_Db_Expr('COUNT(*)')))
			->where('parent_id = ?',$this->_data['id'])
			->query()
			->fetchColumn();
	}
	
	/**
	 *  获取直接父分类
	 * 
	 *  @return Zend_Db_Table_Row_Abstract|null
	 */
	public function getParent()
	{
		return $this->_table->fetchRow(
			$this->_table->select()
				->where('id = ?',$this->_data['parent_id'])
		);
	}
	
	/**
	 *  获取全部父分类
	 * 
	 *  @return array
	 */
	public function getParents()
	{
		$parents = array();
		$tmp = $this;
		while ($parent = $tmp->getParent()) 
		{
			$parents[$parent->level] = $parent;
			$tmp = $parent;
		}
		ksort($parents);
		return $parents;
	}
	
	/**
	 *  获取直接子类
	 * 
	 *  @return Zend_Db_Table_Rowset_Abstract
	 */
	public function getChildren()
	{
		return $this->_table->fetchAll(
			$this->_table->select()
				->where('parent_id = ?',$this->_data['id'])
		);
	}
	
	/**
	 *  获取所有同级分类(包括自己)
	 * 
	 *  @return Zend_Db_Table_Rowset_Abstract
	 */
	public function getSiblings()
	{
		return $this->_table->fetchAll(
			$this->_table->select()
				->where('parent_id = ?',$this->_data['parent_id'])
		);
	}
	
	/**
	 *  获取全部子分类
	 * 
	 *  @return array
	 */
	public function getAllChildren()
	{
		$rets = array();
		$children = $this->getChildren();
		$rets = array_merge($rets,$children->toArray());
		
		foreach ($children as $child) 
		{
			if ($child->hasChildren()) 
			{
				$rets = array_merge($rets,$child->getAllChildren());
			}
		}
		
		return $rets;
	}
}

?>