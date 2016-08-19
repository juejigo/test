<?php

/**
 *  检验参数
 */
function params(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->params = array_merge($request->getQuery(),$request->getUserParams());
	
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
				'notEmptyMessage' => '请选择新闻',
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'news',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('新闻不存在')
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
				'notEmptyMessage' => '请选择新闻',
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'news',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('新闻不存在')
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
			'cate_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择分类',
				array('DbRowExists',array(
					'table' => 'news_cate',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('分类错误')
			),
			'title' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入标题',
			),
			'image' => array(
				'presence' => 'required'
			),
			'content' => array(
				'presence' => 'required'
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
			'cate_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择分类',
				array('DbRowExists',array(
					'table' => 'news_cate',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('分类错误')
			),
			'title' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入标题',
			),
			'image' => array(
				'presence' => 'required'
			),
			'content' => array(
				'presence' => 'required'
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