<?php

/**
 *  检验表单
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = $request->getPost();
	
	if ($action == 'index') 
	{
		/* 构造验证器 */
		$filters = array(
			'page' => 'Int',
			'perpage' => 'Int',
			'cate_id' => 'Int'
		);
		$validators = array(
			'area' => array(
				'default' => 0
			),
			'q' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入关键字',
			),
			'cate_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'product_cate',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('分类错误'),
			),
			'sort' => array(
				array('InArray',array('dateline','sells','pricea','priced')),
				'default' => 'dateline'
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