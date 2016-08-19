<?php
require_once('includes/function/location.php');
require_once('includes/function/product.php');

/**
 *  检验表单
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = array_merge($request->getQuery(),$request->getPost());
	
	if ($action == 'confirm') 
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
		return items($controller);
	}
	else if ($action == 'create') 
	{
		/* 构造验证器 */
		$filters = array();
		$validators = array(
		    'tourist_ids' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '旅客ID不能为空',
		     ),
		    'buyer_name' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '购买人姓名为空',
		    ),
		    'mobile' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '电话为空',
		        'MobileNumber',
		    ),
		    'service_type' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '客服类型不能为空',
		    ),
		    'room_ids' => array(
		        'presence' => 'required',
		        'allowEmpty' => false,
		        'notEmptyMessage' => '房间为空',
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

	return false;
}

/**
 *  检验类
 */
function items(&$controller)
{
	$data = $controller->data;
	
	if (!empty($data['items'])) 
	{
		/* 完整性检验 */
		
		if (!is_array($data['items'])) 
		{
			$controller->error->add('items','表单错误');
			return false;
		}
		
		/* 验证器检验 */
		
		$items = array();
		foreach ($data['items'] as $item) 
		{
			/* 构造验证器 */
			$filters = array(
				'id' => 'Int',
			);
			$validators = array(
				'id' => array(
					'presence' => 'required',
					'allowEmpty' => false,
					'notEmptyMessage' => '请选择产品',
				),
			    'is_adult' => array(
			        'presence' => 'required',
			        'allowEmpty' => false,
			        'notEmptyMessage' => '请选择大人还是小孩',
			    ),
				'num' => array(
					'presence' => 'required',
					'allowEmpty' => false,
					'notEmptyMessage' => '请选择产品数量',
				),
			);
			$input = new Core_Filter_Input($filters,$validators,$item);
			
			/* 验证器检验 */
			if (!$input->isValid()) 
			{
				$controller->error->import($input->getMessages());
			}
			
			if ($controller->error->hasError()) 
			{
				return false;
			}
			
			$items[] = $input->getEscaped();
		}
		$data['items'] = $items;
		
		$controller->data = $data;
	}
	
	if (!empty($data['room_ids']))
	{
	    
	    /* 完整性检验 */
	
	    if (!is_array($data['room_ids']))
	    {
	        $controller->error->add('room_ids','房间选择');
	        return false;
	    }
	
	    /* 验证器检验 */
	
	    $items = array();
	    foreach ($data['room_ids'] as $item)
	    {
	        
	        /* 构造验证器 */
	        $filters = array();
	        $validators = array();
	        $input = new Core_Filter_Input($filters,$validators,$item);
	         
	        /* 验证器检验 */
	        if (!$input->isValid())
	        {
	            $controller->error->import($input->getMessages());
	        }
	         
	        if ($controller->error->hasError())
	        {
	            return false;
	        }
	         
	        $items[] = $input->getEscaped();
	    }
	    $data['room_ids'] = $items;

	    $controller->data = $data;
	}

	return true;
}

/**
 *  检验类
 */
function orderitems(&$controller)
{
	$data = $controller->data;
	
	/* 完整性检验 */
	
	if (!is_array($data['order_items'])) 
	{
		$controller->error->add('order_items','表单错误');
		return false;
	}
	
	/* 验证器检验 */
	
	$orderItmes = array();
	foreach ($data['order_items'] as $item) 
	{
		/* 构造验证器 */
		$filters = array(
			'id' => 'Int',
			'num' => 'Int'
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择产品',
				array('DbRowExists',array(
					'table' => 'order_item',
					'field' => 'id',
					'where' => array('order_id = ?' => $controller->input->id)
				)),
				'messages' => array('产品错误'),
				'breakChainOnFailure' => true
			),
			'num' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择产品数量',
				array('GreaterThan','0'),
				'default' => 1,
				'breakChainOnFailure' => true
			),
			'limit' => array(
				'fields' => array('item_id','num'),
				new Limit()
			)
		);
		$input = new Core_Filter_Input($filters,$validators,$item);
		
		/* 验证器检验 */
		if (!$input->isValid()) 
		{
			$controller->error->import($input->getMessages());
		}
		
		if ($controller->error->hasError()) 
		{
			return false;
		}
		
		$orderItmes[] = $input->getEscaped();
	}
	$data['order_items'] = $orderItmes;
	
	$controller->data = $data;
	
	return true;
}

?>