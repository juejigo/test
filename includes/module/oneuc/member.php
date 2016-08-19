<?php 
/**
 *  检验参数
 */
function params(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->params = $request->getQuery();

	if ($action == 'index')
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
	else if ($action == 'userinfo')
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

	if ($op == 'saveinfo')
	{
		/* 构造验证器 */

		$filters = array(
			'provinece_id' =>'Int',
			'city_id' => 'Int',
			'county_id' => 'Int',
		);
		$validators = array(
			'name' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入姓名',
			),
			'mobile' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入手机号',
			),
			'province_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择省份',
			),
			'city_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择市',
			),
			'county_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择区县',
			),
			'post_code' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入邮编',
			),
			'address' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入详细地址',
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