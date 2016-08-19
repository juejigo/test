<?php

/**
 *  检验表单
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = array_merge($request->getQuery(),$request->getPost());
	
	if ($action == 'index') 
	{
		/* 构造验证器 */
		$filters = array(
			'v' => 'Int',
			'page' => 'Int'
		);
		$validators = array(
			'v' => array(
				array('InArray',array(1,2)),
				'default' => 1
			),
 			'page' => array(
				array('GreaterThan','0'),
				'messages' => array('page必须大于0'),
				'default' => '1'
			),
			'perpage' => array(
				array('GreaterThan','0'),
				'messages' => array('perpage必须大于0'),
				'default' => '10'
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