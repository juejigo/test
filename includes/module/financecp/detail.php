<?php
function params(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->params = $request->getQuery();
	
	if ($action == 'flow') 
	{
		/* 构造验证器 */
		
		$filters = array(
			'page' => 'Int',
			'out_id' => 'Int'
		);
		$validators = array(
			'page' => array(
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('GreaterThan',0),
				'messages' => array('参数错误'),
				'default' => '1'
			),
			'dateline_from' => array(
				'Date'
			),
			'dateline_to' => array(
				'Date'
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
}
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