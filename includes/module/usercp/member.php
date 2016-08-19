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
			'role' => array(
				'default' => ''
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
		/* 构造验证器 */
		$filters = array(
			'id' => 'Int'
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'member',
					'field' => 'id',
				)),
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
	
	if ($action == 'add') 
	{
		/* 构造验证器 */
		$filters = array(
			'group' => 'Int',
			'status' => 'Int',
			'sex' => 'Int',
			'province_id' => 'Int',
			'city_id' => 'Int',
			'county_id' => 'Int',
		);
		$validators = array(
			'account' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入账号',
				new RegisterAccount($action)
			),
			'password' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入密码',
				array('StringLength',array('encoding' => 'UTF-8','min' => '6')),
				'messages' => array('密码不能少于6位')
			),
			'role' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择等级',
				array('InArray',array('member','supplier','admin','seller','partner')),
				'breakChainOnFailure' => true
			),
			'group' => array(
				'presence' => 'required',
			),
			'rolegroup' => array(
				'fields' => array('role','group'),
				new Rolegroup(),
			),
			'status' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择状态',
				array('InArray',array('-1','0','1'))
			),
			'deadline' => array(
				'presence' => 'required',
				'Date'
			),
			'member_name' => array(
				'presence' => 'required',
			),
			'alias' => array(
				'presence' => 'required',
			),
			'sex' => array(
				'presence' => 'required',
				array('InArray',array(0,1))
			),
			'mobile' => array(
				'presence' => 'required',
				'MobileNumber'
			),
			'telephone' => array(
				'presence' => 'required',
				'TelephoneNumber'
			),
			'province_id' => array(
				'presence' => 'required',
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => '1')
				))
			),
			'city_id' => array(
				'presence' => 'required',
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => '2')
				))
			),
			'county_id' => array(
				'presence' => 'required',
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => '3')
				))
			),
			'address' => array(
				'presence' => 'required',
			),
			'memo' => array(
				'presence' => 'required',
			),
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
	else if ($action == 'edit') 
	{
		/* 构造验证器 */
		$filters = array(
			'referee_id' => 'Int',
			'status' => 'Int',
			'group' => 'Int',
			'sex' => 'Int',
			'province_id' => 'Int',
			'city_id' => 'Int',
			'county_id' => 'Int',
		);
		$validators = array(
			'referee_id' => array(
				array('DbRowExists',array(
					'table' => 'member',
					'field' => 'id',
				)),
				'messages' => array('推荐人不存在')
			),
			'account' => array(
				new RegisterAccount($action,$controller->paramInput->id)
			),
			'password' => array(
				'presence' => 'required',
				array('StringLength',array('encoding' => 'UTF-8','min' => '6')),
				'messages' => array('密码不能少于6位')
			),
			'role' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择等级',
				array('InArray',array('member','supplier','admin','seller','partner')),
				'breakChainOnFailure' => true
			),
			'group' => array(
				'presence' => 'required',
			),
			'rolegroup' => array(
				'fields' => array('role','group'),
				new Rolegroup(),
			),
			'status' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择状态',
				array('InArray',array('-1','0','1'))
			),
			'deadline' => array(
				'presence' => 'required',
				'Date'
			),
			'member_name' => array(
				'presence' => 'required',
			),
			'alias' => array(
				'presence' => 'required',
			),
			'sex' => array(
				'presence' => 'required',
				array('InArray',array(0,1))
			),
			'mobile' => array(
				'presence' => 'required',
				'MobileNumber'
			),
			'telephone' => array(
				'presence' => 'required',
				'TelephoneNumber'
			),
			'province_id' => array(
				'presence' => 'required',
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => '1')
				))
			),
			'city_id' => array(
				'presence' => 'required',
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => '2')
				))
			),
			'county_id' => array(
				'presence' => 'required',
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => '3')
				))
			),
			'address' => array(
				'presence' => 'required',
			),
			'memo' => array(
				'presence' => 'required',
			),
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
	else if ($action == 'code') 
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
				array('DbRowExists',array(
					'table' => 'member',
					'field' => 'id',
					'where' => array('role IN (?)' => array('supplier','salesman'))
				)),
				'messages' => array('会员不存在'),
				'breakChainOnFailure' => true
			),
			'code' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入编码',
				'breakChainOnFailure' => true
			),
			'memberCode' => array(
				'fields' => array('id','code'),
				new MemberCode(),
			)
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
	else if ($action == 'bankcard') 
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
				array('DbRowExists',array(
					'table' => 'member',
					'field' => 'id',
					'where' => array('role IN (?)' => array('supplier','salesman'))
				)),
				'messages' => array('会员不存在'),
			),
			'bank' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入银行信息',
			),
			'bankcard' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入银行卡',
			),
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
 *  检验 ajax
 */
function ajax(&$controller)
{
	$request = $controller->getRequest();
	$op = $request->getQuery('op','');
	$controller->data = $request->getPost();

	if ($op == 'update')
	{
		/* 构造验证器 */

		$filters = array(
			'id' => 'Int',
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '用户ID不允许为空',
				array('DbRowExists',array(
					'table' => 'member',
					'field' => 'id',
				)),
				'messages' => array('会员不存在'),
			),
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
			$groups['member'] = array(0,);
			$groups['supplier'] = array(0);
			$groups['seller'] = array(0);
			$groups['partner'] = array(0);
			$groups['admin'] = array(0);
			
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