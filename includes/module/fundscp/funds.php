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
			'status' => 'Int',
			'auth' => 'Int',
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
			'status' => array(
				array('InArray',array('',0,1)),
				'default' => ''
			),		
			'auth' => array(
				array('InArray',array('',0,1,-1)),
				'default' => ''
			),	
			'dateline_from' => array(
 
			   	new DateVerify(),
				'breakChainOnFailure' => true
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
	}else if($action == 'edit'){
		/* 构造验证器 */
		
		$filters = array(
		'id' => 'Int' 
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '页面错误',
				array('DbRowExists',array(
					'table' => 'funds',
					'field' => 'id',
					'where' => array('type = ?' => 0)
				)),
				'messages' => array('页面错误'),
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
			'auth' => 'Int',
 			'status' => 'Int', 		
			
		);
		$validators = array(
			'page' => array(
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('GreaterThan',0),
				'messages' => array('参数错误'),
				'default' => '1'
			),
			'auth' => array(
				array('InArray',array('',0,1)),
				'default' => ''
			),
			'status' => array(
				array('InArray',array('',0,1)),
				'default' => ''
			),		
			'dateline_to' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择截至日期',
			   	new DateVerify(),
				'breakChainOnFailure' => true
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
    if ($action == 'edit') 
	{ 
		/* 构造验证器 */
		$filters = array(
		 'id' => 'Int',
	 
		); 
 
		$validators = array( 
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '页面错误',
				array('DbRowExists',array(
					'table' => 'funds',
					'field' => 'id',
					'where' => array('type = ?' => 0)
				)),
				'messages' => array('页面错误'), 
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
	else if ($action == 'check') 
	{ 
		/* 构造验证器 */
		$filters = array(
		 'id' => 'Int',
	     'status' => 'Int',
		 'auth' => 'Int',
		); 
    
		$validators = array( 
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '页面错误',
				array('DbRowExists',array(
					'table' => 'funds',
					'field' => 'id',
					'where' => array('type = ?' => 0)
				)),
				'messages' => array('页面错误'), 
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
	
	else if ($action == 'batch') 
	{ 
		/* 构造验证器 */
		$filters = array(
		
		 'id' => 'Int',
		  
		); 
    
		$validators = array( 
			'id' => array( 
				array('DbRowExists',array(
					'table' => 'funds',
					'field' => 'id',
					'where' => array('type = ?' => 0)
				)),
				'messages' => array('页面错误'), 
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

?>