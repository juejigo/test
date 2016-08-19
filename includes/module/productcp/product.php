<?php

require_once('includes/function/product.php');

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
			'area' => 'Int',
			'price_from' => 'Float',
			'price_to' => 'Float',
		);
		$validators = array(
			'page' => array(
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('GreaterThan',0),
				'messages' => array('参数错误'),
				'default' => '1'
			),
			'area' => array(
				'default' => 0
			),
			'dateline_from' => array(
				'Date'
			),
			'dateline_to' => array(
				'Date'
			),
			'status' => array(
				array('InArray',array(-1,0,1,2,3)),
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
    else if($action == 'exportitem') 
	{
		/* 构造验证器 */
		
		$filters = array(
			'page' => 'Int',
			'area' => 'Int',
			'price_from' => 'Float',
			'price_to' => 'Float',
		);
		$validators = array(
			'page' => array(
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('GreaterThan',0),
				'messages' => array('参数错误'),
				'default' => '1'
			),
			'area' => array(
				'default' => 0
			),
			'dateline_from' => array(
				'Date'
			),
			'dateline_to' => array(
				'Date'
			),
			'status' => array(
				array('InArray',array(-1,0,1,2,3)),
				'default' => 2
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
				'notEmptyMessage' => '请选择商品',
				array('DbRowExists',array(
					'table' => 'product',
					'field' => 'id',
				)),
				'messages' => array('请选择商品'),
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
				'notEmptyMessage' => '请选择商品',
				array('DbRowExists',array(
					'table' => 'product',
					'field' => 'id',
				)),
				'messages' => array('请选择商品'),
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
	else if ($action == 'addon')
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
	            )),
	            'messages' => array('请选择商品'),
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
	else if ($action == 'contract')
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
	            )),
	            'messages' => array('请选择商品'),
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
	else if ($action == 'trip')
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
	            )),
	            'messages' => array('请选择商品'),
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
	else if ($action == 'edittrip')
	{
	    /* 构造验证器 */
	
	    $filters = array();
	    $validators = array( );
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
	else if ($action == 'ticket')
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
	            )),
	            'messages' => array('请选择商品'),
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
	else if ($action == 'visa')
	{
	    /* 构造验证器 */
	
	    $filters = array(   );
	    $validators = array( );
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
	else if ($action == 'room')
	{
		/* 构造验证器 */
	
		$filters = array( 
			'id' => 'Int',
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
	else if ($action == 'editroom')
	{
		/* 构造验证器 */
	
		$filters = array(
			'id' => 'Int',
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择房间',
				array('DbRowExists',array(
						'table' => 'product_addon',
						'field' => 'id',
				)),
				'messages' => array('请选择房间'),
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
		    'type_id' => 'Int',
		    'price' => 'Float',
		    'cost_price' => 'Float',
		    'features_content' => 'allowedTags',
		    'cost_need' => 'allowedTags',
		);
		$validators = array( 
		    'cate_id' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请选择分类',
		    ),
		    'tourism_type' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请选择旅行方式',
		    ),
		    'features_info' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请填写产品特色摘要',
		    ),
		    'product_name' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请填写标题',
		    ),
		    'brief' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请填写副标题',
		    ),
		    'supplier_id' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请选择供应商',
		    ),
		    'price' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请输入价格',
		        array('GreaterThan','-0.99'),
		        'messages' => array('请输入价格'),
		        'default' => '0'
		    ),
		    'city_id' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请选择出发城市',
		        array('DbRowExists',array(
		            'table' => 'region',
		            'field' => 'id',
		            'where' => array('level = ?' => 2)
		        )),
		        'messages' => array('城市错误'),
		    ),
		    'sn' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请输入货号',
		        'breakChainOnFailure' => true,
		        new Sn()
		    ),
		    'cost_price' => array(
		        'presence' => 'required',
		        array('GreaterThan','-0.99'),
		        'messages' => array('请输入成本价'),
		        'default' => '0'
		    ),
		    'mktprice' => array(
		        'presence' => 'required',
		        'default' => '0'
		    ),
		    'stock' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请输入限购人数',
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

		return items($controller);
	}
	if ($action == 'edit')
	{
	    /* 构造验证器 */
	
	    $filters = array(
	        'type_id' => 'Int',
	        'price' => 'Float',
	        'cost_price' => 'Float',
	        'features_content' => 'allowedTags',
	        'cost_need' => 'allowedTags',
	        'product_name' => 'allowedTags'
	    );
	    $validators = array(
	        'cate_id' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请选择分类',
	        ),
	       'tourism_type' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请选择旅行方式',
		    ),
		    'features_info' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请填写产品特色摘要',
		    ),
		    'product_name' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请填写标题',
		    ),
		    'brief' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请填写副标题',
		    ),
	        'supplier_id' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请选择供应商',
	        ),
	        'city_id' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请选择出发城市',
	            array('DbRowExists',array(
	                'table' => 'region',
	                'field' => 'id',
	                'where' => array('level = ?' => 2)
	            )),
	            'messages' => array('城市错误'),
	        ),
	        'sn' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请输入货号',
	            'breakChainOnFailure' => true,
	        ),
	        'price' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请输入价格',
	            array('GreaterThan','-0.99'),
	            'messages' => array('请输入价格'),
	            'default' => '0'
	        ),
	        'cost_price' => array(
	            'presence' => 'required',
	            array('GreaterThan','-0.99'),
	            'messages' => array('请输入成本价'),
	            'default' => '0'
	        ),
	        'mktprice' => array(
	            'presence' => 'required',
	            'default' => '0'
	        ),
	        'stock' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请输入限购人数',
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
	
	  	return items($controller);
	}
	if ($action == 'edittrip')
	{
	    /* 构造验证器 */
	
	    $filters = array(
	        'id' => 'Int',
	    );
	    $validators = array(
	        'title' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写标题',
	        ),
	        'sort' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写第几天',
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
	else if ($action == 'audit') 
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
					'where' => array('status = ?' => 0)
				)),
				'messages' => array('请选择商品'),
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
	else if ($action == 'down') 
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
				'messages' => array('请正确选择商品'),
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
	else if ($action == 'up') 
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
					'where' => array('status IN (?)' => array(1,3,0))
				)),
				'messages' => array('请正确选择商品'),
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
	else if ($action == 'weight') 
	{
		/* 构造验证器 */
		
		$filters = array(
			'id' => 'Int',
			'weight' => 'Int'
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择商品',
				array('DbRowExists',array(
					'table' => 'product',
					'field' => 'id'
				)),
				'messages' => array('请选择商品'),
			),
			'weight' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入权重',
				array('GreaterThan',-1),
				'messages' => array('权重必须大于0'),
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
	else if ($action == 'editroom')
	{
		/* 构造验证器 */
		$filters = array(
			'id' => 'Int',
			'num' => 'Int',
			'stock' => 'Int',
			'price' => 'Float',
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '房间不为空',
				array('DbRowExists',array(
					'table' => 'product_addon',
					'field' => 'id',
				)),
				'messages' => array('房间出错'),
			),
			'addon_name' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入房间名',
			),
			'image' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请上传图片',
			),
			'num' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入可住人数',
				array('GreaterThan','-0.99'),
				'messages' => array('请输入正确可住人数'),
			),
			'stock' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入库存',
				array('GreaterThan','-0.99'),
				'messages' => array('请输入正确库存'),
			),
			'price' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入销售价',
				array('GreaterThan','-0.99'),
				'messages' => array('请输入销售价'),
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
	
	if ($op == 'catechanged') 
	{
		/* 构造验证器 */
		
		$filters = array(
			'cate_id' => 'Int',
		);
		$validators = array(
			'cate_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择类型',
				array('DbRowExists',array(
					'table' => 'product_cate',
					'field' => 'id',
					'where' => array('status = ?' => 1),
				)),
				'messages' => array('请选择分类'),
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
	else if ($op == 'add_product')
	{
	   /* 构造验证器 */
		
		$filters = array(
		    'type_id' => 'Int',
		    'price' => 'Float',
		    'mktprice' => 'Float',
		    'cost_price' => 'Float',
		    'child_price' => 'Float',
		    'features_content' => 'allowedTags',
		    'cost_need' => 'allowedTags',
		    'stock'=> 'Float',
		);
		$validators = array( 
		    'cate_id' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请选择分类',
		    ),
		    'tourism_type' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请选择旅行方式',
		    ),
		    'product_name' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请填写标题',
		    ),
		    'brief' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请填写副标题',
		    ),
		    'supplier_id' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请选择供应商',
		    ),
		    'city_id' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请选择出发城市',
		        array('DbRowExists',array(
		            'table' => 'region',
		            'field' => 'id',
		            'where' => array('level = ?' => 2)
		        )),
		        'messages' => array('城市错误'),
		    ),
		    'sn' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请输入货号',
		        'breakChainOnFailure' => true,
		        new Sn()
		    ),
		    'mktprice' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请输入成本价',
		        array('GreaterThan','-0.99'),
		        'messages' => array('请输入成本价'),
		        'default' => '0'
		    ),
            'cost_price' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请输入市场价',
	            array('GreaterThan','-0.99'),
	            'messages' => array('请输入市场价'),
	            'default' => '0'
	        ),
	        'price' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请输入销售价',
	            array('GreaterThan','-0.99'),
	            'messages' => array('请输入销售价'),
	            'default' => '0'
	        ),
		    'child_price' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请输入儿童价',
		        array('GreaterThan','-0.99'),
		        'messages' => array('请输入儿童价'),
		        'default' => '0'
		    ),
		    'stock' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请输入限购人数',
		        array('GreaterThan','-0.99'),
		        'messages' => array('请输入限购人数'),
		        'default' => '0'
		    ),
		    'up_time' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请填写上架时间',
		    ),
		    'down_time' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请填写下架时间',
		    ),
		    'travel_restrictions' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请填写出行人数限制',
		    ),
		    'features_info' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请填写产品特色摘要',
		    ),
		    'features_content' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请填写产品特色',
		    ),
		    'cost_need' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请填写费用需知',
		    ),
		    'seo_title' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请填写搜索相关标题',
		    ),
		    'seo_keywords' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请填写搜索相关关键字',
		    ),
			'tags' => array(
				array('DbRowExists',array(
					'table' => 'product_tag',
					'field' => 'id',
					'where' => array('status = ?' => 1)
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

		return items($controller);
	}
	else if ($op == 'edit_product')
	{
	    /* 构造验证器 */
	
	    $filters = array(
	        'type_id' => 'Int',
	        'price' => 'Float',
	        'mktprice' => 'Float',
		    'cost_price' => 'Float',
	        'features_content' => 'allowedTags',
	        'cost_need' => 'allowedTags',
	        'child_price' => 'Float',
	        'stock'=> 'Float',
	    );
	    $validators = array(
	        'cate_id' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请选择分类',
	        ),
	        'tourism_type' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请选择旅行方式',
	        ),
	        'product_name' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写标题',
	        ),
	        'brief' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写副标题',
	        ),
	        'supplier_id' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请选择供应商',
	        ),
	        'city_id' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请选择出发城市',
	            array('DbRowExists',array(
	                'table' => 'region',
	                'field' => 'id',
	                'where' => array('level = ?' => 2)
	            )),
	            'messages' => array('城市错误'),
	        ),
  	        'sn' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请输入货号',
	            'breakChainOnFailure' => true,
	        ),
	        'cost_price' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请输入市场价',
	            array('GreaterThan','-0.99'),
	            'messages' => array('请输入市场价'),
	            'default' => '0'
	        ),
	        'price' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请输入销售价',
	            array('GreaterThan','-0.99'),
	            'messages' => array('请输入销售价'),
	            'default' => '0'
	        ),
	        'mktprice' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请输入成本价',
	            array('GreaterThan','-0.99'),
	            'messages' => array('请输入成本价'),
	            'default' => '0'
	        ),
	        'child_price' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请输入儿童价',
	            array('GreaterThan','-0.99'),
	            'messages' => array('请输入儿童价'),
	            'default' => '0'
	        ),
	        'stock' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请输入限购人数',
	            array('GreaterThan','-0.99'),
	            'messages' => array('请输入限购人数'),
	            'default' => '0'
	        ),
	        'up_time' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写上架时间',
	        ),
	        'down_time' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写下架时间',
	        ),
	        'travel_restrictions' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写出行人数限制',
	        ),
	        'features_info' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写产品特色摘要',
	        ),
	        'features_content' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写产品特色',
	        ),
	        'cost_need' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写费用需知',
	        ),
	        'seo_title' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写搜索相关标题',
	        ),
	        'seo_keywords' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写搜索相关关键字',
	        ),
    		'tags' => array(
    			array('DbRowExists',array(
					'table' => 'product_tag',
   					'field' => 'id',
    				'where' => array('status = ?' => 1)
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
	
	    return items($controller);
	}
	else if ($op == 'catelist')
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
	else if ($op == 'authsn')
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
	else if ($op == 'addonadd')
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
	else if ($op == 'addtrip')
	{
	    /* 构造验证器 */
	    $filters = array(
        'porduct_id' => 'Int',
	    );
	    $validators = array(
	        'title' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请选择类型',
	        ),
	        'sort' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请选择类型',
	        ),
	        'porduct_id' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请选择类型',
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
	else if ($op == 'deletetrip')
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
	else if ($op == 'addticket')
	{
	    /* 构造验证器 */
	    $filters = array(
	         'product_id' => 'Int',
	    );
	    $validators = array(
	          'type' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '请选择类型',
		    ),
	        'go_time' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请选择时间',
	        ),
	        'flight' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写航班',
	        ),
	        'go_area' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写出发地',
	        ),
	        'go_airport' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写出发机场',
	        ),
	        'return_area' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写目的地',
	        ),
	        'return_airport' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写目的地机场',
	        ),
	        'price' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写价格',
	            array('GreaterThan','-0.99'),
	            'messages' => array('请填写价格'),
	            'default' => '0'
	        ),
	        'company' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写航空公司',
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
	else if ($op == 'editticket')
	{
	    /* 构造验证器 */
	    $filters = array(
	        'product_id' => 'Int',
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
	else if ($op == 'deitvalticket')
	{
	    /* 构造验证器 */
	    $filters = array(
	        'id' => 'Int',
	    );
	    $validators = array(
	        'type' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请选择类型',
	        ),
	        'go_time' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请选择时间',
	        ),
	        'flight' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写航班',
	        ),
	        'go_area' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写出发地',
	        ),
	         
	        'go_airport' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写出发机场',
	        ),
	        'return_area' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写目的地',
	        ),
	        'return_airport' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写目的地机场',
	        ),
	        'price' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写价格',
	            array('GreaterThan','-0.99'),
	            'messages' => array('请填写价格'),
	            'default' => '0'
	        ),
	        'company' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写航空公司',
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
	else if ($op == 'deleteticket')
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
	else if ($op == 'addcontract')
	{
	    /* 构造验证器 */
	    $filters = array(
	        'contract_id' => 'Int',
	    );
	    $validators = array(
	        'contract_id' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请选择合同',
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
	else if ($op == 'deleteaddon')
	{
	    /* 构造验证器 */
	    $filters = array(
	    	'pordId' => 'Int',
	    	'insId' => 'Int',
	    );
	    $validators = array(
	        'pordId' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '商品ID不存在',
	        ),
	    	'insId' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '附件ID不存在',
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
	else if ($op == 'deletecontract')
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
	else if ($op == 'addspecval')
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
	else if ($op == 'addspec') 
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
	else if ($op == 'addvisa')
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
	else if ($op == 'deletevisa')
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
	else if ($op == 'addmultispec') 
	{
		/* 构造验证器 */
		$filters = array(
			'cate_id' => 'Int',
			'index' => 'Int'
		);
		$validators = array(
			'cate_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择类型',
				array('DbRowExists',array(
					'table' => 'product_cate',
					'field' => 'id',
					'where' => array('status = ?' => 1),
				)),
				'messages' => array('请选择分类'),
			),
			'from' => array(
				'presence' => 'required',
				'default' => 1
			),
			'to' => array(
				'presence' => 'required',
			),
			'fromto' => array(
				new Fromto(),
			),
			'art' => array(
				'default' => ''
			),
			'product_name' => array(
				'default' => ''
			),
			'price' => array(
				'default' => ''
			),
			'cost_price' => array(
				'default' => ''
			),
			'mktprice' => array(
				'default' => ''
			),
			'stock' => array(
				'default' => ''
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
	else if ($op == 'order')
	{
		/* 构造验证器 */
		$filters = array(
			'id' => 'Int',
			'order' =>'Int',
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
	else if ($op == 'addroom')
	{
		/* 构造验证器 */
		$filters = array(
			'product_id' => 'Int',
			'num' => 'Int',
			'stock' => 'Int',
			'price' => 'Float',
		);
		$validators = array(
			'product_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '商品不为空',
				array('DbRowExists',array(
					'table' => 'product',
					'field' => 'id',
				)),
				'messages' => array('商品出错'),
			),
			'addon_name' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入房间名',
			),
			'image' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请上传图片',
			),
			'num' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入可住人数',
				array('GreaterThan','-0.99'),
				'messages' => array('请输入正确可住人数'),
			),
			'stock' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入库存',
				array('GreaterThan','-0.99'),
				'messages' => array('请输入正确库存'),
			),
			'price' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入销售价',
				array('GreaterThan','-0.99'),
				'messages' => array('请输入销售价'),
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
	else if ($op == 'deleteroom')
	{
		/* 构造验证器 */
		$filters = array(
			'id' => 'Int',
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择房间',
				array('DbRowExists',array(
						'table' => 'product_addon',
						'field' => 'id',
				)),
				'messages' => array('请选择房间'),
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
 *  验证 extra 数组
 */
function extra(&$controller)
{
	$data = $controller->data;
	
	if (empty($data['extra'])) 
	{
		$controller->error->add('extra','表单错误');
		return false;
	}
	
	/* 完整性检验 */
	
	if (!is_array($data['extra'])) 
	{
		$controller->error->add('extra','表单错误');
		return false;
	}
	
	/* 验证器检验 */
	
	// 构造验证器
	
	$filters = array();
	$validators = array(
		'fee_desc' => array(
			'default' => ''
		),
		'book_desc' => array(
			'default' => ''
		),
		'refund_desc' => array(
			'default' => ''
		),
		'notice' => array(
			'default' => ''
		),
	);
	$input = new Core_Filter_Input($filters,$validators,$data['extra']);
	
	// 验证器检验
	
	if (!$input->isValid()) 
	{
		$controller->error->import($input->getMessages());
		return false;
	}
	
	$extra = array();
	$extra['fee_desc'] = $input->getEscaped('fee_desc');
	$extra['book_desc'] = $input->getEscaped('book_desc');
	$extra['refund_desc'] = $input->getEscaped('refund_desc');
	$extra['notice'] = $input->getEscaped('notice');
	$data['extra'] = $extra;
	$controller->data = $data;
	
	return true;
}

/**
 *  验证 items 数组
 */
function items(&$controller)
{
	$item_s=$data = $controller->data;

	if (!empty($data['item_s'])) 
	{
	    if (!is_array($data['item_s']))
	    {
	        $controller->error->add('item','表单错误');
	        return false;
	    }

		/* 验证器检验 */
		
		$items = array();
		
		foreach ($data['item_s'] as $item) 
		{
			// 构造验证器
			foreach ($item['row'] as $data)
			{
			    $filters = array(
			        'price' => 'Float',
			        'mktprice' => 'Float',
			        'cost_price' => 'Float',
			        'child_price' => 'Float',
			        'stock' => 'Float'
			    );
			    $validators = array(
			        'fn' => array(
			            'presence' => 'required',
			            'allowEmpty' => false,
			            'notEmptyMessage' => '请填写规格货号',
			        ),
			        'item_name' => array(
			            'presence' => 'required',
			            'allowEmpty' => false,
			            'notEmptyMessage' => '请填写规格商品名',
			        ),
    			    'price' => array(
    			        'presence' => 'required',
    			        'allowEmpty' => false,
    			        'notEmptyMessage' => '请输入规格产品售价',
    			        array('GreaterThan','-0.99'),
    			        'messages' => array('请输入规格产品售价'),
    			    ),
    			    'cost_price' => array(
    			        'presence' => 'required',
    			        'allowEmpty' => false,
    			        'notEmptyMessage' => '请输入规格产品成本价',
    			        array('GreaterThan','-0.99'),
    			        'messages' => array('请输入规格产品成本价'),
    			    ),
    			    'mktprice' => array(
    			        'presence' => 'required',
    			        'allowEmpty' => false,
    			        'notEmptyMessage' => '请输入规格产品市场价',
    			        array('GreaterThan','-0.99'),
    			        'messages' => array('请输入规格产品市场价'),
    			    ),
			        'child_price' => array(
			            'presence' => 'required',
			            'allowEmpty' => false,
			            'notEmptyMessage' => '请输入规格产品儿童价',
			            array('GreaterThan','-0.99'),
			            'messages' => array('请输入规格产品儿童价'),
			        ),
    			    'stock' => array(
    			        'presence' => 'required',
			            'allowEmpty' => false,
    			        'notEmptyMessage' => '请输入规格产品库存',
	       		        array('GreaterThan','-1'),
        		        'messages' => array('请输入规格产品库存'),
        		        'default' => '0'
    			    ),
    			    'image' => array(
    			        'default' => '',
    			    ),
			     );
			    
			$input = new Core_Filter_Input($filters,$validators,$data);
			// 验证器检验
			
			if (!$input->isValid())
			{
			    $controller->error->import($input->getMessages());
			    return false;
			}
			}

		}

	}

	$controller->data = $item_s;
	return true;
}

/**
 *  检验类
 */
class SpecValue extends Core_Validate_Abstract 
{
	const NOT_ALLOW_EMPTY = 'notAllowEmpty';
	const NOT_VALID = 'notValid';
	
	/**
	 *  @var array
	 */ 
	protected $_messageTemplates = array(
		self::NOT_ALLOW_EMPTY => '请选择产品规格',
		self::NOT_VALID => '产品规格错误',
	);
	
	/**
	 *  构造
	 */
	public function __construct($cateId)
	{
		parent::__construct();
		
		$this->_vars['cate_id'] = $cateId;
	}
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($values)
	{
		$typeId = $this->_db->select()
			->from(array('c' => 'product_cate'),array('type_id'))
			->where('c.id = ?',$this->_vars['cate_id'])
			->query()
			->fetchColumn();
		$type = $this->_db->select()
			->from(array('t' => 'product_type'))
			->where('t.id = ?',$typeId)
			->query()
			->fetch();
		
		for ($i = 1;$i <= 3;$i ++)
		{
			$specId = $type["spec_{$i}"];
			
			if ($specId > 0 && empty($values["specval_{$i}"])) 
			{
				$this->_error(self::NOT_ALLOW_EMPTY);
				return false;
			}
			
			if (isset($values["specval_{$i}"])) 
			{
				$exists = $this->_db->select()
					->from(array('v' => 'product_specval'),array(new Zend_Db_Expr('COUNT(*)')))
					->where('v.spec_id = ?',$specId)
					->where('v.value = ?',$values["specval_{$i}"])
					->query()
					->fetchColumn();
				if ($exists == 0) 
				{
					$this->_error(self::NOT_VALID);
					return false;
				}
			}
		}
		
		return true;
	}
}

/**
 *  检验类
 */
class Fromto extends Core_Validate_Abstract 
{
	const NOT_VALID = 'notValid';
	
	/**
	 *  @var array
	 */ 
	protected $_messageTemplates = array(
		self::NOT_VALID => '参数错误',
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($values)
	{
		if (!empty($values['from']) && !empty($values['to'])) 
		{
			if ($values['from'] >= $values['to']) 
			{
				$this->_error(self::NOT_VALID);
				return false;
			}
		}
		
		return true;
	}
}

/**
 *  检验类
 */
class Sn extends Core_Validate_Abstract 
{
	const SUPPLIER_NO_EXISTS = 'supplierNoExists';
	
	/**
	 *  @var array
	 */ 
	protected $_messageTemplates = array(
		self::SUPPLIER_NO_EXISTS => '商品货号重复',
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $value
	 *  @return boolean
	 */
	public function isValid($value)
	{
		if (!empty($value)) 
		{
			$supplierCode = getSupplierCode($value);
			
			$supplierId = $this->_db->select()
				->from(array('m' => 'product'),array('id'))
				->where('m.sn = ?',$supplierCode)
				->where('m.status <> ?',-1)
				->where('m.parent_id = ?',0)
				->query()
				->fetchColumn();
				
			if (!empty($supplierId)) 
			{
				$this->_error(self::SUPPLIER_NO_EXISTS);
				return false;
			}
		}
		
		return true;
	}
}

?>