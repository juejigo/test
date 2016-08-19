<?php

require_once('includes/function/location.php');

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
		);
		$validators = array(
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
	else if ($action == 'add') 
	{
		/* 构造验证器 */
		
		$filters = array(

		);
		$validators = array(

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
	else if ($action == 'insert') 
	{
		/* 构造验证器 */
		
		$filters = array(
			'province_id' => 'Int',
			'city_id' => 'Int',
			'county_id' => 'Int'
		);
		$validators = array(
			'consignee' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入收货人',
			),
			'province_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择省份',
				array('DbRowExists',array(
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => 1)
				)),
				'messages' => array('省份错误'),
			),
			'city_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择城市',
				array('DbRowExists',array(
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => 2)
				)),
				'messages' => array('城市错误'),
			),
			'county_id' => array(
				'presence' => 'required',
				array('DbRowExists',array(
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => 3),
					'allowEmpty' => true,
				)),
				'messages' => array('区县错误'),
			),
			'address' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入详细地址',
			),
			'zip' => array(
				'presence' => 'required',
				'ZipCode'
			),
			'mobile' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入手机号码',
				'MobileNumber'
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
			'id' => 'Int',
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择收货人',
				array('DbRowExists',array(
					'table' => 'consignee',
					'field' => 'id',
					'where' => array('member_id = ?' => Zend_Auth::getInstance()->getIdentity()->id)
				)),
				'messages' => array('请选择收货人'),
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
	else if ($action == 'update') 
	{
		/* 构造验证器 */
		
		$filters = array(
			'id' => 'Int',
			'province_id' => 'Int',
			'city_id' => 'Int',
			'county_id' => 'Int'
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择收货人',
				array('DbRowExists',array(
					'table' => 'consignee',
					'field' => 'id',
					'where' => array('member_id = ?' => Zend_Auth::getInstance()->getIdentity()->id)
				)),
				'messages' => array('请选择收货人'),
			),
			'consignee' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入收货人',
			),
			'province_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择省份',
				array('DbRowExists',array(
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => 1)
				)),
				'messages' => array('省份错误'),
			),
			'city_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择城市',
				array('DbRowExists',array(
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => 2)
				)),
				'messages' => array('城市错误'),
			),
			'county_id' => array(
				'presence' => 'required',
				array('DbRowExists',array(
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => 3)
				)),
				'messages' => array('区县错误'),
				'allowEmpty' => true,
			),
			'address' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入详细地址',
			),
			'zip' => array(
				'presence' => 'required',
				'ZipCode'
			),
			'mobile' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入手机号码',
				'MobileNumber'
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
	else if ($action == 'setdefault') 
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
					'table' => 'consignee',
					'field' => 'id',
					'where' => array('member_id = ?' => Zend_Auth::getInstance()->getIdentity()->id,'status = ?' => 1)
				)),
				'messages' => array('收货人不存在'),
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
					'table' => 'consignee',
					'field' => 'id',
					'where' => array('member_id = ?' => Zend_Auth::getInstance()->getIdentity()->id,'status = ?' => 1)
				)),
				'messages' => array('收货人不存在'),
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