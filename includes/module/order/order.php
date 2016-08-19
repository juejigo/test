<?php

require_once('includes/function/location.php');

/**
 *  检验参数
 */
function params(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->params = $request->getQuery();
	
	if ($action == 'feedback') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
					'where' => array('buyer_id = ?' => Zend_Auth::getInstance()->getIdentity()->id,'status = ?' => Model_Order::TRADE_FINISHED,'feedback = ?' => 0)
				)),
				'messages' => array('订单状态错误'),
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
	else if ($action == 'refund') 
	{
		/* 构造验证器 */
		
		$filters = array(
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
					'where' => array('buyer_id = ?' => Zend_Auth::getInstance()->getIdentity()->id,'status IN (?)' => array(Model_Order::WAIT_SELLER_SEND_GOODS,Model_Order::WAIT_BUYER_CONFIRM_GOODS))
				)),
				'messages' => array('订单状态错误'),
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
	else if ($action == 'return_product') 
	{
		/* 构造验证器 */
		
		$filters = array(
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
					'where' => array('buyer_id = ?' => Zend_Auth::getInstance()->getIdentity()->id,'status = ?' => Model_Order::WAIT_BUYER_RETURN_GOODS)
				)),
				'messages' => array('订单状态错误'),
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
	else if ($action == 'return') 
	{
		/* 构造验证器 */
		
		$filters = array(
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
					'where' => array('buyer_id = ?' => Zend_Auth::getInstance()->getIdentity()->id,'status = ?' => Model_Order::WAIT_BUYER_RETURN_GOODS)
				)),
				'messages' => array('订单状态错误'),
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
	else if ($action == 'list')
	{
	    /* 构造验证器 */
	
	    $filters = array();
	    $validators = array(
	        'page' => array(
	            array('GreaterThan','0'),
	            'messages' => array('page必须大于0'),
	            'default' => '1'
	        ),
	        'perpage' => array(
	            array('GreaterThan','0'),
	            'messages' => array('perpage必须大于0'),
	            'default' => '10'
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
	else if ($action == 'detail')
	{
	    /* 构造验证器 */
	
	    $filters = array(
	    );
	    $validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
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
	
	    $filters = array();
	    $validators = array(
	        'id' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '参数错误',
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
	else if ($action == 'goalipay')
	{
	    /* 构造验证器 */
	
	    $filters = array();
	    $validators = array(
	        'id' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '参数错误',
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
	else if ($action == 'confirm')
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
	
	    return items($controller);
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
	$controller->data = array_merge($request->getQuery(),$request->getPost());
	
	if ($action == 'confirm') 
	{
		/* 构造验证器 */
		$filters = array(
			'item_id' => 'Int',
		);
		$validators = array(
			'item_id' => array(
				new ItemId(),
				'breakChainOnFailure' => true
			),
			'num' => array(
				array('GreaterThan','0'),
				'default' => 1,
				'breakChainOnFailure' => true
			),
			'stock' => array(
				'fields' => array('item_id','num'),
				new Stock()
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
		
		return carts($controller);
	}
	else if ($action == 'create') 
	{
		/* 构造验证器 */
		$filters = array(
			'item_id' => 'Int',
			'coupon_user_id' => 'Int',
			'point_pay' => 'Int'
		);
		$validators = array(
			'item_id' => array(
				new ItemId(),
			),
			'num' => array(
				array('GreaterThan','0'),
				'default' => 1,
				'breakChainOnFailure' => true
			),
			'mobile' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入手机号码',
				'MobileNumber'
			),
			'point_pay' => array(
				'presence' => 'required',
				new PointPay(),
				'default' => 0
			),
			'stock' => array(
				'fields' => array('item_id','num'),
				new Stock()
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
		
		return carts($controller);
	}
	else if ($action == 'pay') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
					'where' => array('buyer_id = ?' => Zend_Auth::getInstance()->getIdentity()->id)
				)),
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
	else if ($action == 'gopay') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
					//'where' => array('buyer_id = ?' => Zend_Auth::getInstance()->getIdentity()->id,'status = ?' => Model_Order::WAIT_BUYER_PAY)
				)),
				'messages' => array('订单状态错误'),
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
	else if ($action == 'getopenid') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
					//'where' => array('buyer_id = ?' => Zend_Auth::getInstance()->getIdentity()->id,'status = ?' => Model_Order::WAIT_BUYER_PAY)
				)),
				'messages' => array('订单状态错误'),
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
	else if ($action == 'beforpay') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
					//'where' => array('buyer_id = ?' => Zend_Auth::getInstance()->getIdentity()->id,'status = ?' => Model_Order::WAIT_BUYER_PAY)
				)),
				'messages' => array('订单状态错误'),
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
	else if ($action == 'gowxpay') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
					//'where' => array('buyer_id = ?' => Zend_Auth::getInstance()->getIdentity()->id,'status = ?' => Model_Order::WAIT_BUYER_PAY)
				)),
				'messages' => array('订单状态错误'),
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
	else if ($action == 'paycomplet') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
					//'where' => array('buyer_id = ?' => Zend_Auth::getInstance()->getIdentity()->id)
				)),
				'messages' => array('订单状态错误'),
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
	else if ($action == 'list') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
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
			'status' => array(
				array('InArray',array('',Model_Order::WAIT_BUYER_PAY,Model_Order::WAIT_SELLER_SEND_GOODS,Model_Order::TRADE_FINISHED,Model_Order::WAIT_SELLER_AGREE,Model_Order::WAIT_BUYER_RETURN_GOODS,Model_Order::WAIT_BUYER_CONFIRM_GOODS,Model_Order::REFUND_SUCCESS,Model_Order::SELLER_REFUSE_BUYER,Model_Order::WAIT_SELLER_CONFIRM_GOODS)),
				'default' => '',
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
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
					'where' => array('buyer_id = ?' => Zend_Auth::getInstance()->getIdentity()->id)
				)),
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
	else if ($action == 'finish') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
					'where' => array('buyer_id = ?' => Zend_Auth::getInstance()->getIdentity()->id,'status IN (?)' => array(Model_Order::WAIT_SELLER_SEND_GOODS,Model_Order::WAIT_BUYER_CONFIRM_GOODS))
				)),
				'messages' => array('订单状态错误'),
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
	else if ($action == 'shipping') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
					'where' => array('buyer_id = ?' => Zend_Auth::getInstance()->getIdentity()->id,'status = ?' => Model_Order::WAIT_BUYER_CONFIRM_GOODS)
				)),
				'messages' => array('订单状态错误'),
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
	else if ($action == 'feedback') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
					'where' => array('buyer_id = ?' => Zend_Auth::getInstance()->getIdentity()->id,'status = ?' => Model_Order::TRADE_FINISHED,'feedback = ?' => 0)
				)),
				'messages' => array('订单状态错误'),
			),
			'product_id' => array(
				'presence' => 'required',
				'notEmptyMessage' => '产品必须',
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
		
		return feedbacks($controller);
	}
	else if ($action == 'refund') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
					'where' => array('buyer_id = ?' => Zend_Auth::getInstance()->getIdentity()->id,'status IN (?)' => array(Model_Order::WAIT_SELLER_SEND_GOODS,Model_Order::WAIT_BUYER_CONFIRM_GOODS))
				)),
				'messages' => array('订单状态错误'),
			),
			'refund_reason' => array(
				'presence' => 'required',
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
	else if ($action == 'return_product') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
					'where' => array('buyer_id = ?' => Zend_Auth::getInstance()->getIdentity()->id,'status = ?' => Model_Order::WAIT_BUYER_RETURN_GOODS)
				)),
				'messages' => array('订单状态错误'),
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
	else if ($action == 'return') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
					'where' => array('buyer_id = ?' => Zend_Auth::getInstance()->getIdentity()->id,'status = ?' => Model_Order::WAIT_BUYER_RETURN_GOODS)
				)),
				'messages' => array('订单状态错误'),
			),
			'shipping_company' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入快递公司',
			),
			'shipping_no' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入快递单号',
			),
			'memo' => array(
				'presence' => 'required',
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
	else if ($action == 'cancle') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
					'where' => array('buyer_id = ?' => Zend_Auth::getInstance()->getIdentity()->id,'status = ?' => Model_Order::WAIT_BUYER_PAY)
				)),
				'messages' => array('订单状态错误'),
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
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
					'where' => array('buyer_id = ?' => Zend_Auth::getInstance()->getIdentity()->id,'status IN (?)' => array(Model_Order::TRADE_FINISHED,Model_Order::REFUND_SUCCESS))
				)),
				'messages' => array('订单状态错误'),
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
 *  验证 feedbacks 数组
 */
function feedbacks(&$controller)
{
	$data = $controller->data;

	/* 先组装再验证 */

	foreach($data['product_id'] as $key=>$product_id){
		$feedback['product_id'] = $product_id;
		$feedback['grade'] = $data['grade'][$key];
		$feedback['content'] = $data['content'][$key];
		$data['feedbacks'][] = $feedback;
	
	}
	
	/* 完整性检验 */
	
	if (empty($data['feedbacks']) && !is_array($data['feedbacks'])) 
	{
		$controller->error->add('feedbacks','表单错误');
		return false;
	}
	
	/* 验证器检验 */
	
	$feedbacks = array();
	foreach ($data['feedbacks'] as $feedback) 
	{
		// 构造验证器
		
		$filters = array(
			'product_id' => 'Int',
			'grade' => 'Float',
		);
		$validators = array(
			'product_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order_item',
					'field' => 'product_id',
					'where' => array('order_id = ?' => $controller->input->id)
				)),
				'messages' => array('订单状态错误'),
			),
			'grade' => array(
				'presence' => 'required',
				array('InArray',array('5','4.5','4','3.5','3','2.5','2','1.5','1','0.5')),
				'default' => '5'
			),
			'content' => array(
				'presence' => 'required',
				'default' => '好评'
			),
		);
		$input = new Core_Filter_Input($filters,$validators,$feedback);
		
		// 验证器检验
		
		if (!$input->isValid()) 
		{
			$controller->error->import($input->getMessages());
			return false;
		}
		
		$feedbacks[] = $input->getEscaped();
	}
	$data['feedbacks'] = $feedbacks;
	
	/* 产品不重复且 */
	
	$productIds = array();
	foreach ($feedbacks as $feedback) 
	{
		if (in_array($feedback['product_id'],$productIds)) 
		{
			$controller->error->add('feedbacks','重复评价');
			return false;
		}
		$productIds[] = $feedback['product_id'];
	}
	
	$controller->data = $data;
	return true;
}

/**
 *  检验类
 */
class ItemId extends Core_Validate_Abstract 
{
	const NOT_FOUND = 'notFound';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::NOT_FOUND => '商品不存在或已下架',
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($value)
	{
		$item = $this->_db->select()
			->from(array('i' => 'product_item'))
			->joinLeft(array('p' => 'product'),'p.id = i.product_id',array())
			->where('i.id = ?',$value)
			->where('p.status = ?',2)
			->where('i.status = ?',1)
			->query()
			->fetch();
		
		if (empty($item)) 
		{
			$this->_error(self::NOT_FOUND);
			return false;
		}
		
		return true;
	}
}

/**
 *  检验类
 */
class Stock extends Core_Validate_Abstract 
{
	const LOW_STOCK = 'lowStock';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::LOW_STOCK => '库存不足'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($values)
	{
		if (!empty($values['item_id']) && !empty($values['num'])) 
		{
			$item = $this->_db->select()
				->from(array('i' => 'product_item'))
				->where('i.id = ?',$values['item_id'])
				->query()
				->fetch();
			
			if ($item['stock'] < $values['num']) 
			{
				$this->_error(self::LOW_STOCK);
				return false;
			}
		}
		
		return true;
	}
}

/**
 *  检验类
 */
class PointPay extends Core_Validate_Abstract 
{
	const NOT_SUFFICIENT = 'notSufficient';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::NOT_SUFFICIENT => '余额不足'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($value)
	{
		$point = $this->_db->select()
			->from(array('m' => 'member'),array('point'))
			->where('m.id = ?',Zend_Auth::getInstance()->getIdentity()->id)
			->query()
			->fetchColumn();
		
		if ($point < $value) 
		{
			$this->_error(self::NOT_SUFFICIENT);
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

    if ($op == 'tourist')
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
   else if ($op == 'addtourist')
    {
        /* 构造验证器 */
    
        $filters = array();
        $validators = array(
            'tourist_name' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请填写姓名',
            ),
            'cert_type' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请选择证件类型',
            ),
            'cert_num' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请填写证件号',
            ),
            'mobile' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请填写手机号码',
                'MobileNumber',
            ),
            'sex' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请填写性别',
            ),
            'cert_deadline' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请填写截止日期',
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
  else  if ($op == 'create')
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

/**
 *  检验类
 */
class Cret extends Core_Validate_Abstract
{
    const ERROR = 'error';
    /**
     *  @var array
     */
    protected $_messageTemplates = array(
        self::ERROR => '身份证错误'
    );

    /**
     *  检验
     *
     *  @param array $values
     *  @return boolean
    */
    public function isValid($values)
    {
        if (!empty($values['cert_type']) && !empty($values['cert_num']))
        {
            if($values['cert_type'] == 1)
            {
                $idCard = new Core2_Validate_Idcard();
                $row =  $idCard->isValid($values['cert_num']);
                if(!$row)
                {
                    $this->_error(self::ERROR);
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
function items(&$controller)
{
    $data = $controller->params;

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

        $controller->params = $data;
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
            $filters = array(
                'id' => 'Int',
            );
            $validators = array(
                'id' => array(
                    'presence' => 'required',
                    'allowEmpty' => false,
                    'notEmptyMessage' => '请选择产品',
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
        $data['room_ids'] = $items;

        $controller->params = $data;
    }

    return true;
}





?>