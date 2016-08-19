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
			'page' => 'Int'
		);
		$validators = array(
			'page' => array(
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
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
					'table' => 'product_brand',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('请选择品牌'),
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
	else if ($action == 'delete') 
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
					'table' => 'product_brand',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('请选择品牌'),
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
			'brand_name' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入品牌名',
				array('DbRowNoExists',array(
					'table' => 'product_brand',
					'field' => 'brand_name',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('品牌已存在'),
			),
			'types' => array(
				array('DbRowExists',array(
					'table' => 'product_type',
					'field' => 'id',
				)),
				'messages' => array('关联类型不存在'),
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
			'brand_name' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入品牌名',
				array('DbRowNoExists',array(
					'table' => 'product_brand',
					'field' => 'brand_name',
					'where' => array('status = ?' => 1,'id <> ?' => $controller->paramInput->id)
				)),
				'messages' => array('品牌已存在'),
			),
			'types' => array(
				array('DbRowExists',array(
					'table' => 'product_type',
					'field' => 'id',
				)),
				'messages' => array('关联类型不存在'),
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