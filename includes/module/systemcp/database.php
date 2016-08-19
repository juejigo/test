<?php

/**
 *  检验参数
 */
function params(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$op = $request->getQuery('op','');
	$controller->params = $request->getQuery();

	if ($action == 'ajax')
	{
		if($op == 'backup')
		{
			/* 构造验证器 */
	
			$filters = array(
					'page' => 'Int',
			);
			$validators = array(
					'page' => array(
							'allowEmpty' => false,
							'notEmptyMessage' => '参数错误',
							array('GreaterThan',0),
							'messages' => array('参数错误'),
							'default' => '1'
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
	elseif ($action == 'list')
	{
		/* 构造验证器 */

		$filters = array(
				'page' => 'Int',
		);
		$validators = array(
				'page' => array(
						'allowEmpty' => false,
						'notEmptyMessage' => '参数错误',
						array('GreaterThan',0),
						'messages' => array('参数错误'),
						'default' => '1'
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

	if ($op == 'backup')
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
	elseif ($op == 'delete')
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
	return false;
}
?>