<?php

/**
 *  检验表单
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = array_merge($request->getQuery(),$request->getPost());
	
	if ($action == 'list') 
	{
		/* 构造验证器 */
		$filters = array(
			'parent_id' => 'Int',
		);
		$validators = array(
			'parent_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'product_cate',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('父分类错误'),
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
	else if ($action == 'tree') 
	{
		/* 构造验证器 */
		
		$filters = array(
			'area' => 'Int'
		);
		$validators = array(
			'area' => array(
				array('InArray',array('',0,1)),
				'default' => ''
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
	else if ($action == 'position') 
	{
		/* 构造验证器 */
		$filters = array(
			'cate_id' => 'Int',
		);
		$validators = array(
			'cate_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择分类',
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'product_cate',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('分类错误'),
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
	else if ($action == 'listposition') 
	{
		/* 构造验证器 */
		
		$filters = array(
			'parent_id' => 'Int'
		);
		$validators = array(
			'parent_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择分类',
				array('InArray',array(1,2,3,4,5,6,7,8,9)),
				'default' => 1
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
	else if ($action == 'listposition2') 
	{
		/* 构造验证器 */
		
		$filters = array(
			'parent_id' => 'Int'
		);
		$validators = array(
			'parent_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择分类',
				array('InArray',array(1,2,3,4,5,6,7,8,9)),
				'default' => 1
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
	else if ($action == 'detail') 
	{
		/* 构造验证器 */
		$filters = array(
			'id' => 'Int'
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择分类',
				array('DbRowExists',array(
					'table' => 'product_cate',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('请选择分类'),
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