<?php

/**
 *  表单 检验
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = array_merge($request->getQuery(),$request->getPost());
	
	if ($action == 'callback') 
	{
		/* 构造验证器 */
		$filters = array();
		$validators = array(
//   			'customizedData' => array(
//     			'presence' => 'required',
//     			'allowEmpty' => false,
//     			'notEmptyMessage' => '参数错误',
// 			),
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