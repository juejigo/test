<?php

/**
 *  检验类
 */
class Id extends Core_Validate_Abstract 
{
	const NOT_FOUND = 'notFound';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::NOT_FOUND => '分类不存在'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($value)
	{
		// 允许未分类
		if ($value == 0) 
		{
			return true;
		}
		
		$count = $this->_db->select()
			->from(array('c' => 'product_cate'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('c.id = ?',$value)
			->where('c.status = ?',1)
			->query()
			->fetchColumn();
			
		if ($count == 0) 
		{
			$this->_error(self::NOT_FOUND);
			return false;
		}
		return true;
	}
}

/**
 *  检验表单
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = $request->getPost();
	
	if ($action == 'list') 
	{
		/* 构造验证器 */
		$filters = array(
			'parent_id' => 'Int',
			'num' => 'Int'
		);
		$validators = array(
			'parent_id' => array(
				new Id()),
			'num' => array(
				'default' => '0'),
			'order' => array(
				'default' => 'order DESC')
		);
		$controller->input = new Core_Filter_Input($filters,$validators,$controller->data);
		
		/* 验证器检验 */
		if (!$controller->input->isValid()) 
		{
			$controller->error->import($controller->input->getMessages());
		}
		
		if ($controller->error->hasError()) 
		{
			return false;
		}
		return true;
	}
	else if ($action == 'detail') 
	{
		/* 构造验证器 */
		$filters = array(
			'id' => 'Int'
		);
		$validators = array(
			'id' => array(
				new Id())
		);
		$controller->input = new Core_Filter_Input($filters,$validators,$controller->data);
		
		/* 验证器检验 */
		if (!$controller->input->isValid()) 
		{
			$controller->error->import($controller->input->getMessages());
		}
		
		if ($controller->error->hasError()) 
		{
			return false;
		}
		return true;
	}
	
	return false;
}

?>