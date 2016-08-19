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
			'group_id' => 'Int'
		);
		$validators = array(
			'page' => array(
				array('GreaterThan',0),
				'messages' => array('参数错误'),
				'default' => '1'
			),
			'group_id' => array(
				'presence' => 'required',
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
	
	else if ($action == 'group') 
	{
		/* 构造验证器 */
		$filters = array(
			'page' => 'Int'
		);
		$validators = array(
			'page' => array(
				array('GreaterThan',0),
				'messages' => array('参数错误'),
				'default' => '1'
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
		else if ($action == 'groupedit') 
	{
		/* 构造验证器 */
		$filters = array(
			'id' => 'Int'
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'position_group',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('请选择推荐组'),
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
	else if ($action == 'edit') 
	{
		/* 构造验证器 */
		$filters = array(
			'id' => 'Int'
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'position',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('请选择推荐位'),
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
	
	return false;
}

/**
 *  检验表单
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = $request->getPost();
	
	if ($action == 'add') 
	{
		/* 构造验证器 */
		
		$filters = array(
		);
		$validators = array(
			'position_name' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入推荐位名',
				
			),
			'memo' => array(
				'presence' => 'required',
			),
			'group_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '页面错误',
				array('DbRowExists',array(
					'table' => 'position_group',
					'field' => 'id',
			 
				)),
				'messages' => array('数据不存在'), 
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
	else if ($action == 'groupadd') 
	{
		/* 构造验证器 */
		
		$filters = array(
		);
		$validators = array(
			'position_name' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入推荐组名',
				
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
	else if ($action == 'groupedit') 
	{
		/* 构造验证器 */
	 
		$filters = array(
		);
		$validators = array(
			'group_name' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入推荐组名',
				
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
		);
		$validators = array(
			'position_name' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入推荐位名',
				
			),
			'group_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '页面错误',
				array('DbRowExists',array(
					'table' => 'position_group',
					'field' => 'id',
			 
				)),
				'messages' => array('数据不存在'), 
			),		
				 
			'memo' => array(
				'presence' => 'required',
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