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
				'agent_name' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入代理商名称',
						),
		 		'agent_city_id' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '参数错误',
						array('DbRowNoExists',array(
								'table' => 'member_agent',
								'field' => 'region_id',
						)),
						'messages' => array('该城市已有代理商'),
				),
				'company_name' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入代理商名称',
				),
				'province_id' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请完善代理商所在的省市区',
				),
				'city_id' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请完善代理商所在的省市区',
				),
				'county_id' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请完善代理商所在的省市区',
				),
				'address' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入代理商详细地址',
				),
				'linkman' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入联系人姓名',
				),
				'linkman_mobile' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入联系人手机号码',
						'MobileNumber',
				),
				'telephone' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入座机号码',
				),
				
		);
		$controller->input = new Core_Filter_Input($filters,$validators,$controller->data);

		/* 验证器验证 */
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
	
	if ($action == 'edit')
	{
		/* 构造验证器 */
		$filters = array(
	
		);
		$validators = array(
		 		'agent_name' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入代理商名称',
				),
				'agent_city_id' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '参数错误',
						array('DbRowNoExists',array(
								'table' => 'member_agent',
								'field' => 'region_id',
								'where' => array('region_id != ?',$_REQUEST['agent_city_id'])
						)),
						'messages' => array('该城市已有代理商'),
				),
				'company_name' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入代理商名称',
				),
				'province_id' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请完善代理商所在的省市区',
				),
				'city_id' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请完善代理商所在的省市区',
				),
				'county_id' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请完善代理商所在的省市区',
				),
				'address' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入代理商详细地址',
				),
				'linkman' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入联系人姓名',
				),
				'linkman_mobile' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入联系人手机号码',
						'MobileNumber',
				),
				'telephone' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入座机号码',
				),
	
		);
		$controller->input = new Core_Filter_Input($filters,$validators,$controller->data);
	
		/* 验证器验证 */
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
								'table' => 'member_agent',
								'field' => 'id'
						)),
						'messages' => array('用户不存在'),
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