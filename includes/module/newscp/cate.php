<?php

/**
 *  检验参数
 */
function params(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->params = $request->getQuery();
	
	if ($action == 'add') 
	{
		/* 构造验证器 */
		
		$filters = array(
			'parent_id' => 'Int',
		);
		$validators = array(
			'parent_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'news_cate',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('父分类不存在'),
				'default' => 0
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
					'table' => 'news_cate',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('请选择分类'),
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
					'table' => 'news_cate',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('请选择分类'),
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
			'parent_id' => 'Int',
			'display' => 'Int'
		);
		$validators = array(
			'parent_id' => array(
				'presence' => 'required',
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'news_cate',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('父分类不存在'),
			),
			'cate_name' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入分类名',
			),
			'image' => array(
				'presence' => 'required',
			),
			'display' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入分类名',
				array('InArray',array(0,1))
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
	else if ($action == 'edit') 
	{
		/* 构造验证器 */
		$filters = array(
			'parent_id' => 'Int',
			'display' => 'Int'
		);
		$validators = array(
			'parent_id' => array(
				'presence' => 'required',
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'news_cate',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('父分类不存在'),
			),
			'cate_name' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入分类名',
			),
			'image' => array(
				'presence' => 'required',
			),
			'display' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入分类名',
				array('InArray',array(0,1))
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