<?php

/**
 *  检验参数
 */
function params(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->params = $request->getQuery();
 
	if ($action == 'list') 
	{
		
		/* 构造验证器 */
		$filters = array(
			'page' => 'Int',
		);
		$validators = array(
			'page' => array(
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('GreaterThan','0'),
				'messages' => array('参数错误'),
				'default' => '1'
			),
			'status' => array(
				'default' => '1'
			)
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
	else if ($action == 'edit') 
	{
		
	   $filters = array(
			'member_id' => 'Int'
		);
		$validators = array(
		 	 'member_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '页面错误',
				array('DbRowExists',array(
					'table' => 'member',
					'field' => 'id', 
				)),
				'messages' => array('页面错误'),
			) 
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
	
    if ($action == 'edit') 
	{
		/* 构造验证器 */
	 
		$filters = array(
			'member_id' => 'Int',
		    'status' => 'Int',
		);
		$validators = array(
 	 
			
	 
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

/**
 *  验证类
 */
class RegisterAccount extends Core_Validate_Abstract 
{
	const NOT_VALID = 'notValid';
	const REGISTED = 'registed';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::NOT_VALID => '账号格式错误',
		self::REGISTED => '该账号已注册'
	);
	
	/**
	 *  构造
	 */
	public function __construct($action,$id = 0)
	{
		parent::__construct();
		
		$this->_vars['action'] = $action;
		$this->_vars['id'] = $id;
	}
	
	/**
	 *  检验
	 * 
	 *  @param string $value
	 *  @return boolean
	 */
	public function isValid($value)
	{
		if ($this->_vars['action'] == 'add') 
		{
			/* 检验账号格式 */
			if (!Zend_Validate::is($value,'Account',array(),'Core2_Validate')) 
			{
				$this->_error(self::NOT_VALID);
				return false;
			}
			
			/* 是否已注册 */
			$registed = $this->_db->select()
				->from(array('m' => 'member'),array(new Zend_Db_Expr('COUNT(*)')))
				->where('m.account = ?',$value)
				->query()
				->fetchColumn();
			if ($registed > 0) 
			{
				$this->_error(self::REGISTED);
				return false;
			}
			
			return true;
		}
		else if ($this->_vars['action'] == 'edit')
		{
			/* 检验账号格式 */
			if (!Zend_Validate::is($value,'Account',array(),'Core2_Validate')) 
			{
				$this->_error(self::NOT_VALID);
				return false;
			}
			
			/* 是否已注册 */
			$registed = $this->_db->select()
				->from(array('m' => 'member'),array(new Zend_Db_Expr('COUNT(*)')))
				->where('m.account = ?',$value)
				->where('m.id <> ?',$this->_vars['id'])
				->query()
				->fetchColumn();
			if ($registed > 0) 
			{
				$this->_error(self::REGISTED);
				return false;
			}
			
			return true;
		}
		
		return false;
	}
}

/**
 *  检验类
 */
class Rolegroup extends Core_Validate_Abstract 
{
	const NOT_VALID = 'notValid';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::NOT_VALID => '等级错误'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($values)
	{
		if (!empty($values['role']) && isset($values['group'])) 
		{
			$groups = array();
			$groups['member'] = array(0,1,2,3,4,5);
			$groups['merchant'] = array(0);
			$groups['supplier'] = array(0);
			$groups['salesman'] = array(0);
			$groups['agent'] = array(1,2);
			$groups['admin'] = array(1,2,11,12);
			
			if (!in_array($values['group'],$groups[$values['role']])) 
			{
				$this->_error(self::NOT_VALID);
				return false;
			}
		}
		
		return true;
	}
}

/**
 *  检验类
 */
class memberCode extends Core_Validate_Abstract 
{
	const EXISTS = 'exists';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::EXISTS => '编码已存在'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($values)
	{
		if (!empty($values['id']) && !empty($values['code'])) 
		{
			$count = $this->_db->select()
				->from(array('m' => 'member'),array(new Zend_Db_Expr('COUNT(*)')))
				->where('m.id <> ?',$values['id'])
				->where('m.code = ?',$values['code'])
				->query()
				->fetchColumn();
			
			if ($count > 0) 
			{
				$this->_error(self::EXISTS);
				return false;
			}
		}
		
		return true;
	}
}

?>