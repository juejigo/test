<?php

/**
 *  检验
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = $request->getPost();
	
	if ($action == 'index') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'account' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入用户名',
				'Account',
				array('Db_RecordExists',array('table' => 'member','field' => 'account')),
				'messages' => array('1' => '用户名不存在')
			),
			'password' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入密码'
			),
			'remember' => array(
				'default' => '1'
			),
			/*'captcha' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入验证码',
				'Captcha'
			)*/
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