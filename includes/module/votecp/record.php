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
			'vr_id'=>'Int'
		);
		$validators = array(
			'page' => array(
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('GreaterThan',0),
				'messages' => array('参数错误'),
				'default' => '1'
			),
		    'vr_id' => array(
				array('DbRowExists',array(
					  'allowEmpty' => true,
					  'table' => 'vote',
					  'field' => 'id',		
				)),
					'messages' => array('参数错误'),
				),
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