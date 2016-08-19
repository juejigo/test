<?php

/**
 *  表单 检验
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = array_merge($request->getQuery(),$request->getPost());
	
	if ($action == 'last') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'platform' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('InArray',array('ios','android'))
			),
			'channel' => array(
				array('InArray',array('360market','yingyongbao','baidu','pinpai','others','server')),
				'messages' => array('渠道错误'),
				'default' => 'server'
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
	
	return false;
}

?>