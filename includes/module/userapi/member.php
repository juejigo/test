<?php

/**
 *  检验表单
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = array_merge($request->getQuery(),$request->getPost());
	
	if ($action == 'userinfo') 
	{
		/* 构造验证器 */
		$filters = array(
		);
		$validators = array(
			'session_id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('DbRowExists',array(
					'table' => 'app_session',
					'field' => 'id',
					'where' => array('status = ?' => 1)
				)),
				'messages' => array('会话无效'),
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
	else if ($action == 'listtourist')
	{
	    /* 构造验证器 */
	    $filters = array(
	        'page' => 'Int',
	        'perpage' => 'Int'
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
	else if ($action == 'detailtourist')
	{
	    /* 构造验证器 */
	    $filters = array(
	        'id' => 'Int',
	    );
	    $validators = array(
             'id' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写姓名',
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
	else if ($action == 'addtourist')
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
	        
	        'cert' => array(
	            'fields' => array('cert_type','cert_num'),
	            new Cret()
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
	else if ($action == 'edittourist')
	{
	    /* 构造验证器 */
	    $filters = array();
	    $validators = array(
	        'id' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写姓名',
	        ),
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
	        'cert' => array(
	            'fields' => array('cert_type','cert_num'),
	            new Cret()
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
	else if ($action == 'deletetourist')
	{
	    /* 构造验证器 */
	    $filters = array(
	        'id' => 'Int',
	    );
	    $validators = array(
	        'id' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请填写姓名',
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



?>