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
			'id' => 'Int'
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '期数空',
				array('DbRowExists',array(
					'table' => 'one_phase',
					'field' => 'id',
				)),
				'messages' => array('期数不存在'),
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
				'notEmptyMessage' => '期数空',
				array('DbRowExists',array(
					'table' => 'one_phase',
					'field' => 'id',
				)),
				'messages' => array('期数不存在'),
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
			'product_id' => 'Int',
			'product_price' => 'Float',
			'price' => 'Float',
		);
		$validators = array(
			'product_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入商品ID',
				array('DbRowExists',array(
					'table' => 'product',
					'field' => 'id',
				)),
				'messages' => array('商品ID不存在'),
			),
			'num' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入期数',
			),
			'product_name' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入商品名',
			),
			'image' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请上传图片',
			),
			'product_price' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入商品价格',
				array('GreaterThan','-0.99'),
				'messages' => array('请输入商品价格'),
					
			),
			'price' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请单次购买价格',
				array('GreaterThan','-0.99'),
				'messages' => array('请单次购买价格'),
			),
			'start_time' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入开始时间',
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
	elseif ($action == 'edit')
	{
		/* 构造验证器 */

		$filters = array(
			'product_price' => 'Float',
			'price' => 'Float',
		);
		$validators = array(
			'product_name' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入商品名',
			),
			'image' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请上传图片',
			),
			'product_price' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入商品价格',
				array('GreaterThan','-0.99'),
				'messages' => array('请输入商品价格'),
			),
			'price' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请单次购买价格',
				array('GreaterThan','-0.99'),
				'messages' => array('请单次购买价格'),
			),
			'start_time' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入开始时间',
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
			'id' => 'Int',
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '期数ID不允许为空',
				array('DbRowExists',array(
					'table' => 'one_phase',
					'field' => 'id',
				)),
				'messages' => array('期数ID不存在'),
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