<?php
/**
 *  检验参数
 */
function params(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->params = $request->getQuery();

	if ($action == 'winlist')
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
	else if ($action == 'orderlist')
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
	else if ($action == 'rechargelist')
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
	else if ($action == 'recharge')
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
	else if ($action == 'wxpay')
	{
		/* 构造验证器 */
	
		$filters = array(
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => 'Please choose order',
				array('DbRowExists',array(
					'table' => 'one_order',
					'field' => 'id',
					'where' => array('status = ?' => 0,'type = ?' => 1),
				)),
				'messages' => array('订单状态不正确'),
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
	else if ($action == 'pay')
	{
		/* 构造验证器 */
	
		$filters = array(
			'id' => 'Int',
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => 'Please choose product',
				array('DbRowExists',array(
					'table' => 'one_phase',
					'field' => 'id',
					'where' => array('status = ?' => 1),
				)),
				'messages' => array('期数状态不正确'),
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
	else if ($action == 'paysuccess')
	{
		/* 构造验证器 */
	
		$filters = array(
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => 'Please pay the order',
				array('DbRowExists',array(
					'table' => 'one_order',
					'field' => 'id',
				)),
				'messages' => array('订单状态不正确'),
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
 *  检验 ajax
 */
function ajax(&$controller)
{
	$request = $controller->getRequest();
	$op = $request->getQuery('op','');
	$controller->data = $request->getPost();

	if ($op == 'winlist')
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
	else if ($op == 'orderlist')
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
			'type' => array(
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('InArray',array('all',1,2,3)),
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
	else if ($op == 'rechargelist')
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
	else if ($op == 'wxpay')
	{
		/* 构造验证器 */
	
		$filters = array(
			'money' => 'Float',
		);
		$validators = array(
            'money' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请输入金额',
	            array('GreaterThan','-0.99'),
	            'messages' => array('请输入金额'),
	            'default' => '0'
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
	else if ($op == 'pay')
	{
		/* 构造验证器 */
	
		$filters = array(
			'id' => 'Int',
			'num' => 'Int',
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => 'Please choose product',
				array('DbRowExists',array(
					'table' => 'one_phase',
					'field' => 'id',
					'where' => array('status = ?' => 1),
				)),
				'messages' => array('期数状态不正确'),
			),
			'num' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择数量',
				array('GreaterThan','0.99'),
				'messages' => array('请输入正确数量'),
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