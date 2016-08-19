<?php

class Core_Config extends Zend_Config 
{
	/**
	 *  @var Array
	 */
	protected $_data = array();
	
	/**
	 *  @var Array
	 */
	protected $_idValues = array();
	
	/**
	 *  @var Array
	 */
	protected $_idFields = array();
	
	/**
	 *  @var Core_Tree
	 */
	protected $_tree = null;
	
	public function __construct()
	{
		$db = Zend_Registry::get('db');
		
		$results = $db->select()
			->from(array('c' => 'system_config'))
			->query()
			->fetchAll();
			
		$this->_tree = new Core_Tree();
		$this->_tree->setTree($results,'id','parent_id','field');
		
		foreach ($results as $r)
		{
			$this->_idValues[$r['id']] = $r['value'];
			$this->_idFields[$r['id']] = $r['field'];
		}
		
		$data = array();
		$parents = $this->_tree->getChild(0);
		
		foreach ($parents as $id)
		{
			$data[$this->_idFields[$id]] = $this->_value($id);
		}
		
		// 特殊处理
		$data['session'] = array();
    	
    	parent::__construct($data);
	}
	
	protected function _value($id)
	{
		$child = $this->_tree->getChild($id);
		if (empty($child))
		{
			return $this->_idValues[$id];
		}
		else 
		{
			$ret = array();
			foreach ($child as $childId)
			{
				$ret[$this->_idFields[$childId]] = $this->_value($childId);
			}
			return $ret;
		}
	}
}

?>