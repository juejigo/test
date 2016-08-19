<?php

/**
 *  基本设置 检验
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = array_merge($request->getQuery(),$request->getPost());
	
	if ($action == 'profile') 
	{
		/* 构造检验器 */
		$filters = array(
		);
		$validators = array(
			'member_name' => array(
				'presence' => 'required',
			),
			'sex' => array(
				'presence' => 'required',
				array('InArray',array(0,1))
			),
			'province_id' => array(
				array('DbRowExists',array(
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => 1),
					'allowEmpty' => true,
				)),
				'messages' => array('省份错误'),
				'default' => 0,
			),
			'city_id' => array(
				array('DbRowExists',array(
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => 2),
					'allowEmpty' => true,
				)),
				'messages' => array('城市错误'),
				'default' => 0,
			),
			'county_id' => array(
				array('DbRowExists',array(
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => 3),
					'allowEmpty' => true,
				)),
				'messages' => array('区县错误'),
				'default' => 0,
			),
			'address' => array(
				'default' => '',
			),
		);
		$controller->input = new Core_Filter_Input($filters,$validators,$controller->data);
		
		/* 检验器检验 */
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
		);
		$validators = array(
			'member_name' => array(
				'presence' => 'required',
			),
			'sex' => array(
				'presence' => 'required',
				array('InArray',array(0,1))
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
	else if ($action == 'password') 
	{
		/* 构造检验器 */
		$filters = array(

		);
		$validators = array(
			'ori_password' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入原密码',
				new OriPassword()
			),
			'password' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入密码',
				array('StringLength',array('encoding' => 'UTF-8','min' => '6')),
				'messages' => array('密码不能少于6位')
			)
		);
		$controller->input = new Core_Filter_Input($filters,$validators,$controller->data);
		
		/* 检验器检验 */
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
	else if ($action == 'resetpassword') 
	{
		/* 构造验证器 */
		$filters = array(
			'password' => 'Rsadecode',
		);
		$validators = array(
			'password' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入密码',
				array('StringLength',array('encoding' => 'UTF-8','min' => '6')),
				'messages' => array('密码不能少于6位')
			)
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
	else if ($action == 'mobile') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'mobile' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				'MobileNumber',
				array('DbRowNoExists',array(
					'table' => 'member',
					'field' => 'account',
				)),
				'messages' => array('手机已被注册'),
				'breakChainOnFailure' => true,
			),
			'code' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				'breakChainOnFailure' => true,
			),
			'smscode' => array(
				'fields' => array('mobile','code'),
				new Smscode()
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
	else if ($action == 'email') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'email' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				'EmailAddress',
				array('DbRowNoExists',array(
					'table' => 'member',
					'field' => 'email',
				)),
				'messages' => array('该邮箱已绑定'),
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
 *  内部验证类
 */
class OriPassword extends Core_Validate_Abstract  
{
	const INVALID = 'invalid';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::INVALID => '原密码错误'
	);
	
	/**
	 *  检验
	 * 
	 *  @param string $value
	 *  @return boolean
	 */
	public function isValid($value)
	{
		/* 获取原密码 */
		$user = Zend_Auth::getInstance()->getIdentity();
		$model = new Model_Member();
		$r = $this->_db->select()
			->from(array('m' => 'member'),array('password','salt'))
			->where('m.id = ?',$user->id)
			->query()
			->fetch();
		
		/* 加密验证 */
		if ($model->encodePassword($value,$r['salt']) != $r['password']) 
		{
			$this->_error(self::INVALID);
			return false;
		}
		
		return true;
	}
}

/**
 *  检验类
 */
class Smscode extends Core_Validate_Abstract 
{
	const NOT_VALID = 'notValid';
	
	/**
	 *  @var array
	 */ 
	protected $_messageTemplates = array(
		self::NOT_VALID => '验证码错误'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($values)
	{
		if (!empty($values['mobile']) && !empty($values['code'])) 
		{
			$count = $this->_db->select()
				->from(array('r' => 'app_smscode'),array(new Zend_Db_Expr('COUNT(*)')))
				->where('r.mobile = ?',$values['mobile'])
				->where('r.code = ?',$values['code'])
				->query()
				->fetchColumn();
			
			if ($count == 0) 
			{
				$this->_error(self::NOT_VALID);
				return false;
			}
		}
		
		return true;
	}
}

?>