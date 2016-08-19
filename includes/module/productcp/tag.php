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
	else if ($action == 'taglist')
	{
		/* 构造验证器 */
	
		$filters = array(
			'page' => 'Int',
			'id' => 'Int',
		);
		$validators = array(
			'page' => array(
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('GreaterThan',0),
				'messages' => array('参数错误'),
				'default' => '1'
			),
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择标签',
				array('DbRowExists',array(
					'table' => 'product_tag',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('标签不存在'),
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
				'notEmptyMessage' => '请选择标签',
				array('DbRowExists',array(
					'table' => 'product_tag',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('标签不存在'),
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
	else if ($action == 'tagedit')
	{
		/* 构造验证器 */
	
		$filters = array(
				'productId' => 'Int',
				'tagId' => 'Int'
		);
		$validators = array(
				'productId' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请选择标签',
						array('DbRowExists',array(
								'table' => 'product',
								'field' => 'id',
						)),
						'messages' => array('商品错误！'),
				),
				'tagId' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请选择标签',
						array('DbRowExists',array(
								'table' => 'product_tagdata',
								'field' => 'tag_id',
						)),
						'messages' => array('标签错误！'),
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
				'notEmptyMessage' => '请选择标签',
				array('DbRowExists',array(
					'table' => 'product_tag',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('标签不存在'),
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
			'tag_name' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入标签名',
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
			'tag_name' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入标签名',
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
	else if ($action == 'tagedit')
	{
		/* 构造验证器 */
	
		$filters = array(
		);
		$validators = array(
				'title' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入商品名！',
				),
				'image' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请上传图片！',
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

	if ($op == 'order')
	{
		/* 构造验证器 */
		$filters = array(
			'id' => 'Int',
			'order' =>'Int',
			'tag' => 'Int',
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择商品',
				array('DbRowExists',array(
					'table' => 'product',
					'field' => 'id',
				)),
				'messages' => array('请选择商品'),
			),
			'tag' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择标签',
				array('DbRowExists',array(
					'table' => 'product_tag',
					'field' => 'id',
				)),
				'messages' => array('请选择标签'),
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
			'id' => 'Int',
			'order' =>'Int',
			'tag' => 'Int',
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择商品',
				array('DbRowExists',array(
					'table' => 'product',
					'field' => 'id',
				)),
				'messages' => array('请选择商品'),
			),
			'tag' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择标签',
				array('DbRowExists',array(
					'table' => 'product_tag',
					'field' => 'id',
				)),
				'messages' => array('请选择标签'),
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
	else if ($op == 'delete')
	{
		/* 构造验证器 */
		$filters = array(
			'id' => 'Int',
			'tag' => 'Int',
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择商品',
				array('DbRowExists',array(
					'table' => 'product',
					'field' => 'id',
				)),
				'messages' => array('请选择商品'),
			),
			'tag' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择标签',
				array('DbRowExists',array(
					'table' => 'product_tag',
					'field' => 'id',
				)),
				'messages' => array('请选择标签'),
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
	else if ($op == 'deletedown')
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
	return false;
}
?>