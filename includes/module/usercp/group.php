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
	
	else if ($action == 'edit')
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
				'id' => array(
						array('DbRowExists',array(
								'table' => 'member_group',
								'field' => 'id',
						)),
						'messages' => array('错误！'),
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
 *  检验表单
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = array_merge($request->getQuery(),$request->getPost());
	
	if ($action == 'add') 
	{
		/* 构造验证器 */
		$filters = array(
				'point' => 'Int',
				'consumption' => 'Int',
		);
		$validators = array(
			'name' => array(
					'allowEmpty' => false,
					'notEmptyMessage' => '请输入等级名称',
			),
			'role' => array(
					'allowEmpty' => false,
					'notEmptyMessage' => '请选择角色',
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
	
	else if ($action == 'edit')
	{
		/* 构造验证器 */
		$filters = array(
				'point' => 'Int',
				'consumption' => 'Int',
		);
		$validators = array(
				'name' => array(
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入等级名称',
				),
				'role' => array(
						'allowEmpty' => false,
						'notEmptyMessage' => '请选择角色',
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
 *  检验 ajax
 */
function ajax(&$controller)
{
	$request = $controller->getRequest();
	$op = $request->getQuery('op','');
	$controller->data = $request->getPost();

	if ($op == 'delete')
	{
		/* 构造验证器 */

		$filters = array(
				'id' => 'Int'
		);
		$validators = array(
				'id' => array(
						'presence' => 'required',
						array('DbRowExists',array(
								'table' => 'member_group',
								'field' => 'id'
						)),
						'messages' => array('等级不存在'),
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