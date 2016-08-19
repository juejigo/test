<?php

/**
 *  检验表单
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = $request->getPost();
	
	if ($action == 'list') 
	{
		/* 构造验证器 */
		$filters = array(
			'page' => 'Int',
			'perpage' => 'Int',
			'grade' => 'Float'
		);
		$validators = array(
			'product_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '商品不存在或已下架',
				array('DbRowExists',array(
					'table' => 'product',
					'field' => 'id',
					'where' => array('status = ?' => 2)
				)),
				'messages' => array('商品不存在或已下架'),
			),
			'grade' => array(
				array('InArray',array('',0,1,2)),
				'messages' => array('参数错误'),
				'default' => ''
			),
			'page' => array(
				array('GreaterThan','0'),
				'messages' => array('page必须大于0'),
				'default' => '1'
			),
			'perpage' => array(
				array('GreaterThan','0'),
				'messages' => array('perpage必须大于0'),
				'default' => '20'
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