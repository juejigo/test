<?php

function params(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->params = array_merge($request->getUserParams(),$request->getQuery());
	
	if ($action == 'upload') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'CKEditorFuncNum' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误'),
			'type' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('InArray',array('img')),
				'messages' => array('类型错误'))
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
}

?>