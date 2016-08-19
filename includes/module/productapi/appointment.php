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
		);
		$validators = array(
		    'page' => array(
		        array('GreaterThan','0'),
		        'messages' => array('page必须大于0'),
		        'default' => '1'
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
	else if ($action == 'delete')
	{
	    /* 构造验证器 */
	    $filters = array(
	        'id' => 'Int'
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
	else if ($action == 'create')
	{
	    /* 构造验证器 */
	    $filters = array();
	    $validators = array(
	    	'tourism_type' => array(
    			'presence' => 'required',
    			'allowEmpty' => false,
    			'notEmptyMessage' => '请选择出游方式',
    			array('InArray',array(1,2,3,4,5)),
	    		'messages' => array('出游方式不正确'),
    			'default' => 1,
	    	),
	        'start_time' => array(
        		'presence' => 'required',
        		'allowEmpty' => false,
        		'notEmptyMessage' => '请填写出行日期',
	            'Date',
	        	new RightDate(),
	        ),
	        'destination' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写目的地',
	        ),
	        'phone' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写手机',
	            'MobileNumber',
	        ),
	        'play_days' => array(
	            array('GreaterThan','0'),
				'messages' => array('游玩天数必须大于0'),
				'default' => '1'
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
class RightDate extends Core_Validate_Abstract
{
	const ERROR_DATE = 'errordate';

	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
			self::ERROR_DATE => '预约日期错误'
	);

	/**
	 *  检验
	 *
	 *  @param array $values
	 *  @return boolean
	*/
	public function isValid($value)
	{
		if (strtotime($value)<time())
		{
			$this->_error(self::ERROR_DATE);
			return false;
		}
		return true;
	}
}
?>