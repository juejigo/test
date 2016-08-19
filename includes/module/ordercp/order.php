<?php

require_once('includes/function/product.php');
require_once('includes/function/location.php');
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
			'province_id' => 'Int',
			'city_id' => 'Int',
			'county_id' => 'Int', 
			'page' => 'Int',
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
 
			
			'province_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => 1)
				)),
				'messages' => array('省份错误'),
			),
			
			'city_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => 2)
				)),
				'messages' => array('城市错误'),
			),
			'county_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => 3),
					'allowEmpty' => true,
				)),
				'messages' => array('区县错误'),
			),							 
			
			'from' => array(
				array('InArray',array('',0,1)),
				'default' => ''
			),
			'dateline_from' => array(
 
			   	new DateVerify(),
				'breakChainOnFailure' => true
			),
			'dateline_to' => array(
			   	new DateVerify(),
				'breakChainOnFailure' => true
			),
			'status' => array(
				array('InArray',array('',0,1,2,3,10,11,12,13,14,20)),
				'default' => ''
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
	else if ($action == 'exportorder') 
	{
		/* 构造验证器 */
		
		$filters = array(
			'page' => 'Int',
			'price_from' => 'Float',
			'price_to' => 'Float',
			'province_id' => 'Int',
			'city_id' => 'Int',
			'county_id' => 'Int', 		
			
		);
		$validators = array(
			'page' => array(
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('GreaterThan',0),
				'messages' => array('参数错误'),
				'default' => '1'
			),
			'from' => array(
				array('InArray',array('',0,1)),
				'default' => ''
			),
			
			'province_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => 1)
				)),
				'messages' => array('省份错误'),
			),
			
			'city_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => 2)
				)),
				'messages' => array('城市错误'),
			),
			'county_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => 3),
					'allowEmpty' => true,
				)),
				'messages' => array('区县错误'),
			),							 			
			'dateline_from' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择开始日期',
			     new DateVerify(),
				'breakChainOnFailure' => true
			),
			'dateline_to' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择截至日期',
			   	 new DateVerify(),
				'breakChainOnFailure' => true
			),
			'status' => array(
				array('InArray',array('',0,1,2,3,10,11,12,13,14,20)),
				'default' => ''
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
	else if ($action == 'exportitem') 
	{
		/* 构造验证器 */
		
		$filters = array(
			'page' => 'Int',
			'price_from' => 'Float',
			'price_to' => 'Float',
			'province_id' => 'Int',
			'city_id' => 'Int',
			'county_id' => 'Int', 			
		);
		$validators = array(
			'page' => array(
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('GreaterThan',0),
				'messages' => array('参数错误'),
				'default' => '1'
			),
			'province_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => 1)
				)),
				'messages' => array('省份错误'),
			),
			
			'city_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => 2)
				)),
				'messages' => array('城市错误'),
			),
			'county_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'region',
					'field' => 'id',
					'where' => array('level = ?' => 3),
					'allowEmpty' => true,
				)),
				'messages' => array('区县错误'),
			),
			'from' => array(
				array('InArray',array('',0,1)),
				'default' => ''
			),
			'dateline_from' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择开始日期',
			   	new DateVerify(),
				'breakChainOnFailure' => true
			),
			'dateline_to' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择截至日期',
			   	new DateVerify(),
				'breakChainOnFailure' => true
			),
			'status' => array(
				array('InArray',array('',0,1,2,3,10,11,12,13,14,20)),
				'default' => ''
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
				'notEmptyMessage' => '请选择订单',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
				)),
				'messages' => array('请选择订单'),
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
	else if ($action == 'print') 
	{
		/* 构造验证器 */
		
		$filters = array(
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择订单',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'id',
				)),
				'messages' => array('请选择订单'),
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
	else if ($action == 'shipping') 
	{
		/* 构造验证器 */
		
		$filters = array(
		);
		$validators = array(
			'shipping_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'order_shipping',
					'field' => 'id',
				)),
				'messages' => array('物流信息错误'),
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
	else if ($action == 'alipayrefund')
	{
		/* 构造验证器 */
	
		$filters = array(
		);
		$validators = array(
			'order_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择订单',
				array('DbRowExists',array(
					'table' => 'order',
					'field' => 'out_id',
				)),
				'messages' => array('请选择订单'),
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
	
	if ($action == 'send') 
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
				)),
				'messages' => array('订单状态错误'),
			),
			'company_no' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择快递公司',
			),
			'shipping_no' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入快递单号',
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
	else if ($action == 'agree') 
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
					'where' => array('status = ?' => Model_Order::WAIT_SELLER_AGREE)
				)),
				'messages' => array('订单状态错误'),
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
					'where' => array('status IN (?)' => array(Model_Order::WAIT_SELLER_CONFIRM_GOODS,Model_Order::WAIT_SELLER_AGREE))
				)),
				'messages' => array('订单状态错误'),
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
	else if ($action == 'canclerefund') 
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
					'where' => array('status = ?' => array(Model_Order::WAIT_SELLER_AGREE))
				)),
				'messages' => array('订单状态错误'),
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
	else if ($action == 'refuse') 
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
					'where' => array('status = ?' => Model_Order::WAIT_SELLER_AGREE)
				)),
				'messages' => array('订单状态错误'),
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
					'where' => array('status = ?' => Model_Order::WAIT_BUYER_PAY)
				)),
				'messages' => array('订单状态错误'),
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
	else if ($action == 'discount') 
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
					'where' => array('status = ?' => Model_Order::WAIT_BUYER_PAY,'out_id = ?' => '')    // 订单未支付且没有生成外部订单
				)),
				'messages' => array('订单状态错误'),
				'breakChainOnFailure' => true
			),
			'amount' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请输入金额',
				array('GreaterThan',0),
				'breakChainOnFailure' => true
			),
			'range' => array(
				'fields' => array('id','discount'),
				new Range(),
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
    
    if ($op == 'remarks')
    {
        /* 构造验证器 */
    
        $filters = array();
        $validators = array(
            'id' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '订单id不能为空',
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
    else if ($op == 'editremarks')
    {
        /* 构造验证器 */
    
        $filters = array();
        $validators = array(
            'id' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '订单id不能为空',
            ),
            'remarks' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '备注信息不能为空',
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
    else if ($op == 'confirm_order')
    {
        /* 构造验证器 */
    
        $filters = array();
        $validators = array(
            'id' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '订单id不能为空',
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
    else if ($op == 'refund')
    {
        /* 构造验证器 */
    
        $filters = array();
        $validators = array(
            'id' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '订单id不能为空',
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
    else if ($op == 'shipping')
    {
        /* 构造验证器 */
    
        $filters = array();
        $validators = array(
            'id' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '订单id不能为空',
            ),
            'company_no' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '快递公司不能为空',
            ),
            'shipping_no' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '快递单号不能为空',
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
 *  检验类
 */
class DateVerify extends Core_Validate_Abstract 
{
 	const LOW_STOCK = 'dateline_to';	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
 
		self::LOW_STOCK => '日期格式不正确'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($value)
	{
	 
		$patten = "/^\d{4}[\-](0?[1-9]|1[012])[\-](0?[1-9]|[12][0-9]|3[01])(\s+(0?[0-9]|1[0-9]|2[0-3])\:(0?[0-9]|[1-5][0-9]))?$/";
        $msg=preg_match ($patten, $value);
		if (empty($msg)) { 
			$this->_error(self::LOW_STOCK); 
			return false;
		  }  
	 
		return true;
	}
}


/**
 *  检验类
 */
class Range extends Core_Validate_Abstract 
{
	const OUT_OF_RANGE = 'outOfRange';
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::OUT_OF_RANGE => '不能超过订单金额'
	);
	
	/**
	 *  检验
	 * 
	 *  @param array $values
	 *  @return boolean
	 */
	public function isValid($values)
	{
		if (!empty($values['amount']) && !empty($values['id'])) 
		{
			$payAmount = $this->_db->select()
				->from(array('o' => 'order'),array('pay_amount'))
				->where('o.id = ?',$values['id'])
				->query()
				->fetchColumn();
			
			if ($payAmount < $values['amount']) 
			{
				$this->_error(self::OUT_OF_RANGE);
				return false;
			}
		}
		
		return true;
	}
}

?>