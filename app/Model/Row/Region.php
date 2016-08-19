<?php

class Model_Row_Region extends Zend_Db_Table_Row_Abstract 
{
	/**
	 *  获取直接上级地区
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
	 *  获取全部上级地区
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
	 *  获取所有同级地区(包括自己)
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
	 *  获取直接下级地区
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
	 *  获取完整路径名
	 * 
	 *  @return string
	 */
	public function getPath()
	{
		$parents = $this->getParents();
		
		$path = '';
		foreach ($parents as $parent) 
		{
			$path .= ($path == '') ? $parent->region_name : "{$parent->region_name}";
		}
		$path .= ($path == '') ? $this->_data['region_name'] : "{$this->_data['region_name']}";
		return $path;
	}
}

?>