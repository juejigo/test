<?php

/**
 *  检验表单
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = array_merge($request->getQuery(),$request->getPost());
	
	if ($action == 'register') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'mobile' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入手机号码',
				'MobileNumber',
				array('DbRowNoExists',array(
					'table' => 'member',
					'field' => 'account',
				)),
				'messages' => array(1 => '号码已注册'),
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
	else if ($action == 'findpassword') 
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
				array('DbRowExists',array(
					'table' => 'member',
					'field' => 'account',
				)),
				'messages' => array(1 => '帐号未注册'),
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
		if (!empty($values['account']) && !empty($values['code'])) 
		{
			$count = $this->_db->select()
				->from(array('r' => 'app_smscode'),array(new Zend_Db_Expr('COUNT(*)')))
				->where('r.mobile = ?',$values['account'])
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