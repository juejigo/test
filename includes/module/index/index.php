<?php
/**
 * 根据类型id查询商品
 */
function getCatIdByProduct($cate_id,$limit)
{
	$db = Zend_Registry::get('db');
	
	//查询子类cate_id
	$cateIds  = $db->select()
		->from(array('o' => 'product_cate'),array('id'))
		->where('o.parent_id = ?',$cate_id)
		->where('o.status = ?',1)
		->query()
		->fetchAll();

	if(count($cateIds)<=0)
	{
		//查询子类cate_id
		$cateIds  = $db->select()
			->from(array('o' => 'product_cate'),array('id'))
			->where('o.id = ?',$cate_id)
			->where('o.status = ?',1)
			->query()
			->fetchAll();
	}

	$cate = array();
	foreach ($cateIds as $ids)
	{
		$cate[] = $ids['id'];
	}
	
	//查询和cate_id关联的商品
	$productIds = array();
	if (!empty($cate))
	{
		$productIds = $db->select()
			->from(array('o' => 'product_catedata'),array('product_id','id'))
			->where('o.cate_id in (?)',$cate)
			->query()
			->fetchAll();
	}
	
	if($productIds)
	{
		$product = array();
		foreach ($productIds as $ids)
		{
			$product[] = $ids['product_id'];
		}
		 
		//查询商品
		$productList = $db->select()
			->from(array('o' => 'product'))
			->where('o.id in (?)',array_unique($product))
			->where('o.status <> ?',3)
			->where('o.status <> ?',-1)
			->where('o.status <> ?',0)
			->where('o.parent_id = ?',0)
			->limit($limit)
			->query()
			->fetchAll();
		 
		if($productList)
		{
			return $productList;
		}
		else
		{
			return false;
		}

	}
	else
	{
		return false;
	}
}

//获取位置数据
function getPositionData($positions)
{
	$db = Zend_Registry::get('db');
	
	foreach($positions as $position)
	{
		$id = $position['id'];
		$select = $db->select();
		$positionsData = $select
			->from(array('d' => 'position_data'))
			->where('d.position_id = ?',$position['id'])
			->where('d.status = ?',1)
			->order(array('order asc','id asc'))
			->limit($position['limit'])
			->query()
			->fetchAll();
		foreach ($positionsData as $key=>$positionData) 
		{
			$params = Zend_Serializer::unserialize($positionData['params']);
			
			if($positionData['data_type'] == 'product')
			{
				$positionsData[$key]['url'] = DOMAIN.'product/product/detail/?id='.$params['product_id'];
			}
			else if($positionData['data_type'] == 'product_list')
			{
				$param_str = http_build_query($params);
				$positionsData[$key]['url'] = DOMAIN.'product/product/list?'.$param_str;
			}
			else if($positionData['data_type'] == 'news')
			{
				$positionsData[$key]['url'] = DOMAIN.'news/news/detail/?id='.$params['news_id'];
			}
			else if ($positionData['data_type'] == 'link') 
			{
				$positionsData[$key]['url'] = $params['url'];
			}
		}
		$data[$id] = $positionsData;
	}

	return $data;
}

/**
 *  检验表单
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = $request->getQuery();
	if ($action == 'list') 
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

	return false;
}

function params(&$controller)
{
    $request = $controller->getRequest();
    $action = strtolower($request->getActionName());
    $controller->params = $request->getQuery();
    if ($action == 'list')
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
  else  if ($action == 'about')
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
}

/**
 *  检验 ajax
 */
function ajax(&$controller)
{
    $request = $controller->getRequest();
    $op = $request->getQuery('op','');
    $controller->data = $request->getPost();

    if ($op == 'sendcode')
    {
        /* 构造验证器 */

        $filters = array();
        $validators = array(
            'account' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '参数错误',
                'MobileNumber',
                array('DbRowNoExists',array(
                    'table' => 'member',
                    'field' => 'account',
                )),
                'messages' => array('会员已存在'),
                'breakChainOnFailure' => true,
            ),
            'verifycode' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入验证码',
				'Captcha',
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
   else if ($op == 'verifycode')
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
	else if ($op == 'register')
	{
	    /* 构造验证器 */
	    $filters = array();
	    $validators = array(
                'account' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				'MobileNumber',
				array('DbRowNoExists',array(
					'table' => 'member',
					'field' => 'account',
				)),
				'messages' => array('会员已存在'),
				'breakChainOnFailure' => true,
			),
			'password' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入密码',
			    array('StringLength',array('encoding' => 'UTF-8','min' => '6')),
			    'messages' => array('密码不能少于6位')
			),
			'code' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				'breakChainOnFailure' => true,
			),
			'mobilecode' => array(
				'fields' => array('account','code'),
				new Core2_Validate_Code()
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
	else if ($op == 'hsaaccount')
	{
	    /* 构造验证器 */
	    $filters = array();
	    $validators = array(
	        'account' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '参数错误',
	            'MobileNumber',
	            array('DbRowNoExists',array(
	                'table' => 'member',
	                'field' => 'account',
	            )),
	            'messages' => array('会员已存在'),
	            'breakChainOnFailure' => true,
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
	else if ($op == 'login')
	{
	    /* 构造验证器 */
	    $filters = array();
	    $validators = array(
   			'account' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				'MobileNumber',
				'breakChainOnFailure' => true,
				array('DbRowExists',array(
					'table' => 'member',
					'field' => 'account',
				)),
				'messages' => array('会员不存在'),
			),
     		'password' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入密码',
     		    array('StringLength',array('encoding' => 'UTF-8','min' => '6')),
     		    'messages' => array('密码不能少于6位')
			),
			'remember' => array(
				'default' => '1',
				array('InArray',array(0,1))
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
	if ($op == 'forgetsendcode')
	{
	    /* 构造验证器 */
	
	    $filters = array();
	    $validators = array(
	        'account' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '参数错误',
	        ),
	        'verifycode' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请输入验证码',
	            'Captcha',
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
	if ($op == 'forget')
	{
	    /* 构造验证器 */
	
	    $filters = array();
	    $validators = array(
	        'account' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '参数错误',
	        ),
	        'code' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '参数错误',
	            'breakChainOnFailure' => true,
	        ),
	        'password' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请输入密码',
	            array('StringLength',array('encoding' => 'UTF-8','min' => '6')),
	            'messages' => array('密码不能少于6位')
	        ),
	        'mobilecode' => array(
	            'fields' => array('account','code'),
	            new Core2_Validate_Code()
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