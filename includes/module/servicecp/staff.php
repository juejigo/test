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
	else if ($action == 'add')
	{
		/* 构造验证器 */
	
		$filters = array();
		$validators = array();
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
				'notEmptyMessage' => '请选择客服人员',
				array('DbRowExists',array(
					'table' => 'service_staff',
					'field' => 'id',
				)),
				'messages' => array('请正确选择客服人员'),
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
			'staff_name' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入客服名',
			),
			'image' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请上传图片',
			),
			'wx' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入微信号',
			),
			'phone' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入手机',
			),
			'introduce' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入自我介绍',
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
			'staff_name' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入客服名',
			),
			'image' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请上传图片',
			),
			'wx' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入微信号',
			),
			'phone' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入手机',
			),
			'introduce' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入自我介绍',
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
					'table' => 'service_staff',
					'field' => 'id'
				)),
				'messages' => array('客服 不存在'),
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