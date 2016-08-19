<?php
/**
 *  检验参数
 */
function params(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->params = $request->getQuery();
	
	if ($action == 'sales') 
	{
		/* 构造验证器 */
		
		$filters = array(

		);
		$validators = array(
			'dateline_from' => array(
				'Date'
			),
			'dateline_to' => array(
				'Date'
			)
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
	else if ($action == 'status') 
	{
		/* 构造验证器 */
		
		$filters = array(

		);
		$validators = array(
			'dateline_from' => array(
				'Date'
			),
			'dateline_to' => array(
				'Date'
			)
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
	}		return false;
}
/**
 *  检验ajax
 */

function ajax(&$controller)
{
	$request = $controller->getRequest();
	$op = $request->getQuery('op','');
	$controller->data = $request->getPost();
	
	if ($op == 'sales') 
	{
		/* 构造验证器 */
		
		$filters = array(
		);
		$validators = array(
			'dateline_from' => array(
				'Date'
			),
			'dateline_to' => array(
				'Date'
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
	else if ($op == 'status') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'dateline_from' => array(
				'Date'
			),
			'dateline_to' => array(
				'Date'
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



?>