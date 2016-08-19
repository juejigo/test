<?php

/**
 *  检验参数
 */
function params(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->params = $request->getQuery();
	
	if ($action == 'qrcode')
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
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

	else if ($action == 'findpwd') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array( 
			'account' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				'MobileNumber',
				'breakChainOnFailure' => true,
				array('DbRowExists',array(
					'table' => 'member',
					'field' => 'account',
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

	else if ($action == 'sendcode') 
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
				'messages' => array('号码已注册'),
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
	else if ($action == 'login') 
	{
		/* 构造验证器 */
		$filters = array(
			//'password' => 'Rsadecode'
		);
		$validators = array(
			'verifycode' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入验证码',
				'Captcha',
			),
			'account' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				'MobileNumber',
				'breakChainOnFailure' => true,
				array('DbRowExists',array(
					'table' => 'member',
					'field' => 'account',
				)),
				'messages' => array('会员不存在'),
			),
			'password' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入密码',
				array('StringLength',array('encoding' => 'UTF-8','min' => '6')),
				'messages' => array('密码不能少于6位')
			),
			'remember' => array(
				'default' => '1',
				array('InArray',array(0,1))
			),
		);
		$controller->input = new Core_Filter_Input($filters,$validators,$controller->data);
		/* 验证器检验 */
		if (!$controller->input->isValid()) 
		{
			$controller->error->import($controller->input->getMessages());
		}
		//dump($controller->input->getMessages());exit;
		if ($controller->error->hasError()) 
		{
			return false;
		}
		return true;
	}

	else if ($action == 'findpwdph') 
	{ 
		/* 构造验证器 */
		$filters = array(
			//'password' => 'Rsadecode'
		);
		$validators = array(
			 
			'account' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				'MobileNumber',
				array('DbRowExists',array(
					'table' => 'member',
					'field' => 'account',
				)),
				'messages' => array('会员不存在'),
				'breakChainOnFailure' => true,
			),
 
		);
		$controller->input = new Core_Filter_Input($filters,$validators,$controller->data);
		/* 验证器检验 */
		if (!$controller->input->isValid()) 
		{
			$controller->error->import($controller->input->getMessages());
		}
		//dump($controller->input->getMessages());exit;
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
			//'ori_password' => 'Rsadecode',
			//'password' => 'Rsadecode',
		);
		$validators = array(
			'account' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				'MobileNumber',
				array('DbRowExists',array(
					'table' => 'member',
					'field' => 'account',
				)),
				'messages' => array('会员不存在'),
				'breakChainOnFailure' => true,
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
	else if ($action == 'bindingmb') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'account' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				'MobileNumber',
				array('DbRowNoExists',array(
					'table' => 'member',
					'field' => 'account',
				)),
				'messages' => array('会员已存在'),
				'breakChainOnFailure' => true,
			),
			'password' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入密码',
				array('StringLength',array('encoding' => 'UTF-8','min' => '6')),
				'messages' => array('密码不能少于6位')
			),
			'code' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				'breakChainOnFailure' => true,
			),
			/*
			'mobilecode' => array(
				'fields' => array('account','code'),
				new Core2_Validate_Code()
			),
			*/
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
 *  检验 ajax
 */
function ajax(&$controller)
{
	$request = $controller->getRequest();
	$op = $request->getQuery('op','');
	$controller->data = $request->getPost();

	if ($op == 'sendcode')
	{
		/* 构造验证器 */

		$filters = array();
		$validators = array(
				'account' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '参数错误',
						'MobileNumber',
						array('DbRowNoExists',array(
								'table' => 'member',
								'field' => 'account',
						)),
						'messages' => array('会员已存在'),
						'breakChainOnFailure' => true,
				),
				'verifycode' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入验证码',
						'Captcha',
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
	else if ($op == 'verifycode')
	{
		/* 构造验证器 */

		$filters = array();
		$validators = array();
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
	else if ($op == 'register')
	{
		/* 构造验证器 */
		$filters = array();
		$validators = array(
				'account' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '参数错误',
						'MobileNumber',
						array('DbRowNoExists',array(
								'table' => 'member',
								'field' => 'account',
						)),
						'messages' => array('会员已存在'),
						'breakChainOnFailure' => true,
				),
				'password' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入密码',
						array('StringLength',array('encoding' => 'UTF-8','min' => '6')),
						'messages' => array('密码不能少于6位')
				),
				'code' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '参数错误',
						'breakChainOnFailure' => true,
				),
				'mobilecode' => array(
						'fields' => array('account','code'),
						new Core2_Validate_Code()
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
	else if ($op == 'hasaccount')
	{
		/* 构造验证器 */
		$filters = array();
		$validators = array(
				'account' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '参数错误',
						'MobileNumber',
						array('DbRowNoExists',array(
								'table' => 'member',
								'field' => 'account',
						)),
						'messages' => array('会员已存在'),
						'breakChainOnFailure' => true,
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
	else if ($op == 'login')
	{
		/* 构造验证器 */
		$filters = array();
		$validators = array(
				'account' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '参数错误',
						'MobileNumber',
						'breakChainOnFailure' => true,
						array('DbRowExists',array(
								'table' => 'member',
								'field' => 'account',
						)),
						'messages' => array('会员不存在'),
				),
				'password' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入密码',
						array('StringLength',array('encoding' => 'UTF-8','min' => '6')),
						'messages' => array('密码不能少于6位')
				),
				'remember' => array(
						'default' => '1',
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
	if ($op == 'forgetsendcode')
	{
		/* 构造验证器 */

		$filters = array();
		$validators = array(
				'account' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '参数错误',
				),
				'verifycode' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入验证码',
						'Captcha',
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
	if ($op == 'forget')
	{
		/* 构造验证器 */

		$filters = array();
		$validators = array(
				'account' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '参数错误',
				),
				'code' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '参数错误',
						'breakChainOnFailure' => true,
				),
				'password' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入密码',
						array('StringLength',array('encoding' => 'UTF-8','min' => '6')),
						'messages' => array('密码不能少于6位')
				),
				'mobilecode' => array(
						'fields' => array('account','code'),
						new Core2_Validate_Code()
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


?>