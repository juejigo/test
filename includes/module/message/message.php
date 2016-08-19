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
		self::NOT_FOUND => '留言不存在'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($value)
	{
		$user = Zend_Auth::getInstance()->getIdentity();
		$count = $this->_db->select()
			->from(array('m' => 'message'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('m.id = ?',$value)
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
 *  检验参数
 */
function params(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->params = $request->getUserParams();
	
	if ($action == 'index') 
	{
		/* 构造验证器 */
		$filters = array(
			'page' => 'Int'
		);
		$validators = array(
			'page' => array(
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('GreaterThan','0'),
				'messages' => array('参数错误'),
				'default' => '1')
		);
		$controller->paramInput = new Core_Filter_Input($filters,$validators,$controller->params);
		
		/* 验证器检验 */
		if (!$controller->paramInput->isValid()) 
		{
			$controller->error->import($controller->paramInput->getMessages());
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
		$controller->paramInput = new Core_Filter_Input($filters,$validators,$controller->params);
		
		/* 验证器检验 */
		if (!$controller->paramInput->isValid()) 
		{
			$controller->error->import($controller->paramInput->getMessages());
		}
		
		if ($controller->error->hasError()) 
		{
			return false;
		}
		
		return true;
	}
	else if ($action == 'reply') 
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
		$controller->paramInput = new Core_Filter_Input($filters,$validators,$controller->params);
		
		/* 验证器检验 */
		if (!$controller->paramInput->isValid()) 
		{
			$controller->error->import($controller->paramInput->getMessages());
		}
		
		if ($controller->error->hasError()) 
		{
			return false;
		}
		
		return true;
	}
	
	return false;
}

/**
 *  表单检验
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = $request->getPost();
	
	if ($action == 'reply') 
	{
		/* 构造验证器 */
		$filters = array();
		$validators = array(
			'reply' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入回复')
		);
		$controller->input = new Core_Filter_Input($filters,$validators,$controller->data);
		
		/* 验证器验证 */
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