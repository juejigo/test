<?php

require_once('includes/function/product.php');

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
			'page' => 'Int',
			'perpage' => 'Int',
			'tag_id' => 'Int',
			'cate_id' => 'Int',
		);
		$validators = array(
			'area' => array(
				'default' => '0'
			),
			'tag_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'product_tag',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('标签错误'),
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
			'brand_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'product_brand',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('品牌错误'),
			),
			'sort' => array(
				array('InArray',array('dateline','sells','priced','pricea')),
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
	else if ($action == 'list2') 
	{
		/* 构造验证器 */
		$filters = array(
			'page' => 'Int',
			'perpage' => 'Int',
			'tag_id' => 'Int',
			'cate_id' => 'Int',
		);
		$validators = array(
			'area' => array(
				'default' => '0'
			),
			'tag_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'product_tag',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('标签错误'),
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
			'brand_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'product_brand',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('品牌错误'),
			),
			'sort' => array(
				array('InArray',array('dateline','sells','priced','pricea')),
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
				'notEmptyMessage' => '请选择商品',
				array('DbRowExists',array(
					'table' => 'product',
					'field' => 'id',
					'where' => array('status = ?' => 2)
				)),
				'messages' => array('商品不存在'),
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
	else if ($action == 'detailitems')
	{
	    /* 构造验证器 */
	    $filters = array(
	        'id' => 'Int'
	    );
	    $validators = array(
	        'id' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请选择商品',
	            array('DbRowExists',array(
	                'table' => 'product',
	                'field' => 'id',
	                'where' => array('status = ?' => 2)
	            )),
	            'messages' => array('商品不存在'),
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
	else if ($action == 'trip')
	{
	    /* 构造验证器 */
	    $filters = array();
	    $validators = array();
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
	if ($action == 'recommend') 
	{
		/* 构造验证器 */
		$filters = array(
			'num' => 'Int',
			'product_id' => 'Int',
		);
		$validators = array(
			'num' => array(
				array('GreaterThan','0'),
				'default' => 3,
			),
			'product_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'product',
					'field' => 'id',
					'where' => array('status = ?' => 2)
				)),
				'messages' => array('标签错误'),
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
	else if ($action == 'reserve')
	{
	 	/* 构造验证器 */
		$filters = array(
			'id' => 'Int',
		);
		$validators = array();
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