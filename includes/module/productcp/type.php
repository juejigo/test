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
				'notEmptyMessage' => '请选择类型',
				array('DbRowExists',array(
					'table' => 'product_type',
					'field' => 'id',
				)),
				'messages' => array('请选择类型'),
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
			'spec_1' => 'Int',
			'spec_2' => 'Int',
			'spec_3' => 'Int'
		);
		$validators = array(
			'type_name' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入类型名',
				array('DbRowNoExists',array(
					'table' => 'product_type',
					'field' => 'type_name',
				)),
				'messages' => array('类型已存在'),
			),
			'spec_1' => array(
				'presence' => 'required',
				array('DbRowExists',array(
					'table' => 'product_spec',
					'field' => 'id',
					'allowEmpty' => true,
				)),
				'messages' => array('规格不存在'),
			),
			'spec_2' => array(
				'presence' => 'required',
				array('DbRowExists',array(
					'table' => 'product_spec',
					'field' => 'id',
					'allowEmpty' => true,
				)),
				'messages' => array('规格不存在'),
			),
			'spec_3' => array(
				'presence' => 'required',
				array('DbRowExists',array(
					'table' => 'product_spec',
					'field' => 'id',
					'allowEmpty' => true,
				)),
				'messages' => array('规格不存在'),
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
		
		return attrs($controller);
	}
	else if ($action == 'edit') 
	{
		/* 构造验证器 */
		
		$filters = array(
			'spec_1' => 'Int',
			'spec_2' => 'Int',
			'spec_3' => 'Int'
		);
		$validators = array(
			'type_name' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入类型名',
				array('DbRowNoExists',array(
					'table' => 'product_type',
					'field' => 'type_name',
					'where' => array('id <> ?',$controller->paramInput->id)
				)),
				'messages' => array('类型已存在'),
			),
			'spec_1' => array(
				'presence' => 'required',
				array('DbRowExists',array(
					'table' => 'product_spec',
					'field' => 'id',
					'allowEmpty' => true,
				)),
				'messages' => array('规格不存在'),
			),
			'spec_2' => array(
				'presence' => 'required',
				array('DbRowExists',array(
					'table' => 'product_spec',
					'field' => 'id',
					'allowEmpty' => true,
				)),
				'messages' => array('规格不存在'),
			),
			'spec_3' => array(
				'presence' => 'required',
				array('DbRowExists',array(
					'table' => 'product_spec',
					'field' => 'id',
					'allowEmpty' => true,
				)),
				'messages' => array('规格不存在'),
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
		
		return attrs($controller);
	}
	
	return false;
}

/**
 *  验证 items 数组
 */
function attrs(&$controller)
{
	$data = $controller->data;
	
	$attrs = array();
	if (!empty($data['attrs'])) 
	{
		/* 完整性检验 */
		
		if (!is_array($data['attrs'])) 
		{
			$controller->error->add('attrs','表单错误');
			return false;
		}
		
		/* 过滤无效属性 */
		
		foreach ($data['attrs'] as $i => $attr) 
		{
			if (empty($attr['name'])) 
			{
				unset($data['attrs'][$i]);
			}
			
			$attr['options'] = array_filter(explode(',',str_replace(array('，',';','；'),',',$attr['options'])));
			$data['attrs'][$i] = $attr;
		}
		
		/* 验证器检验 */
		
		foreach ($data['attrs'] as $attr) 
		{
			// 构造验证器
			
			$filters = array(
			);
			$validators = array(
				'name' => array(
					'presence' => 'required',
					'allowEmpty' => false,
					'notEmptyMessage' => '请输入属性名',
				),
				'type' => array(
					'presence' => 'required',
					'allowEmpty' => false,
					'notEmptyMessage' => '请选择属性类型',
					array('InArray',array('select','input')),
					'messages' => array('属性类型错误'),
					'default' => 'select'
				),
				'options' => array(
					'presence' => 'required',
					'allowEmpty' => false,
					'notEmptyMessage' => '请输入属性值',
				)
			);
			$input = new Core_Filter_Input($filters,$validators,$attr);
			
			// 验证器检验
			
			if (!$input->isValid()) 
			{
				$controller->error->import($input->getMessages());
				return false;
			}
			
			$attrs[] = $input->getEscaped();
		}
	}
	$data['attrs'] = $attrs;
	
	$controller->data = $data;
	return true;
}

?>