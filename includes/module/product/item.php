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
		self::NOT_FOUND => '产品不存在'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($value)
	{
		$count = $this->_db->select()
			->from(array('i' => 'product_item'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('i.id = ?',$value)
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
 *  检验类
 */
class ProductId extends Core_Validate_Abstract 
{
	const NOT_FOUND = 'notFound';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::NOT_FOUND => '商品不存在'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($value)
	{
		$count = $this->_db->select()
			->from(array('p' => 'product'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('p.id = ?',$value)
			->where('p.status = ?',2)
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
			'page' => 'Int',
			'perpage' => 'Int',
		);
		$validators = array(
			'id_in' => array(
				new Id()),
			'page' => array(
				array('GreaterThan','0'),
				'messages' => array('page必须大于0'),
				'default' => '1'),
			'perpage' => array(
				array('GreaterThan','0'),
				'messages' => array('perpage必须大于0'),
				'default' => '10'),
			'page_uri' => array(
				'default' => '/')
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
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
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
	else if ($action == 'spec') 
	{
		/* 构造验证器 */
		$filters = array(
			'product_id' => 'Int'
		);
		$validators = array(
			'product_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				new ProductId())
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