<?php

/**
 *  检验表单
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = $request->getPost();
	
	if ($action == 'transfer') 
	{
		/* 构造验证器 */
		$filters = array(
			'amount' => 'Float',
			//'password' => 'Rsadecode'
		);
		$validators = array(
			'account' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输转入帐号',
				array('DbRowExists',array(
					'table' => 'member',
					'field' => 'account',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('帐号不存在'),
			),
			'amount' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输转出金额',
				new Amount(),
				array('GreaterThan','0'),
				'messages' => array('转出金额必须大于0'),
			),
			'password' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入密码',
				new Password()
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
	else if ($action == 'withdrawinsert') 
	{
		/* 构造验证器 */
		$filters = array(
			'amount' => 'Float',
			'password' => 'Rsadecode'
		);
		$validators = array(
			'amount' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入转出金额',
				new Amount(),
				array('GreaterThan','99.9'),
				'messages' => array('转出金额必须大于等于100'),
			),
			'bank_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '银行卡号必须',
				array('DbRowExists',array(
					'table' => 'bankcard',
					'field' => 'id',
					'where' => array('member_id = ?' => Zend_Auth::getInstance()->getIdentity()->id,'status = ?' => 2)
				)),
				'messages' => array('银行卡错误'),				
			),
			'password' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入密码',
				new Password()
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

	else if ($action == 'memberauth') 
	{

		/* 构造验证器 */
		$filters = array(
			'password' => 'Rsadecode'
		);
		$validators = array(
			'name' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '姓名必须',
			),
			'idcard_no' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '身份证号必须',
				'Idcard'
			),
			'mobile' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '手机号必须',
			    'MobileNumber',
			),
			'card_no' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '支付宝帐号必须',
			),
			'img_1' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '身份证照必须（正面）',
			),
			'img_2' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '身份证照必须（背面）',
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
	else if ($action == 'addaccount') 
	{

		/* 构造验证器 */
		$filters = array(
			'password' => 'Rsadecode'
		);
		$validators = array(
			'card_no' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '支付宝帐号必须',
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
 *  检验类
 */
class Amount extends Core_Validate_Abstract 
{
	const NOT_SUFFICIENT = 'notSufficient';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::NOT_SUFFICIENT => '余额不足'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($value)
	{
		$balance = $this->_db->select()
			->from(array('m' => 'member'),array('balance'))
			->where('m.id = ?',Zend_Auth::getInstance()->getIdentity()->id)
			->query()
			->fetchColumn();
		
		if ($balance < $value) 
		{
			$this->_error(self::NOT_SUFFICIENT);
			return false;
		}
		
		return true;
	}
}

/**
 *  内部验证类
 */
class Password extends Core_Validate_Abstract  
{
	const INVALID = 'invalid';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::INVALID => '密码错误'
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


?>