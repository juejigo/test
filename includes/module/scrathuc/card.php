<?php 
/**
 *  检验参数
 */
function params(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->params = array_merge($request->getQuery(),$request->getUserParams());
	
	if ($action == 'list') 
	{
		/* 构造验证器 */
		$filters = array(
			'page' => 'Int'
		);
		$validators = array(
			'page' => array(
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('GreaterThan',0),
				'messages' => array('参数错误'),
				'default' => '1'
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
	else if ($action == 'prizeinfo') 
	{
		/* 构造验证器 */
		$filters = array(
			'id' => 'Int'
		);
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择刮刮卡',
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
	else if ($action == 'use')
	{
	    /* 构造验证器 */
	    $filters = array(
	        'id' => 'Int'
	    );
	    $validators = array(
	        'id' => array(
	            'presence' => 'required',
	            'allowEmpty' => false,
	            'notEmptyMessage' => '请选择刮刮卡',
	            array('DbRowExists',array(
	                'allowEmpty' => true,
	                'table' => 'scrath_card',
	                'field' => 'id',
	                'where' => array('status = ?' => 0)
	            )),
	            'messages' => array('刮刮卡不存在')
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
	            'notEmptyMessage' => '请选择刮刮卡',
	            array('DbRowExists',array(
	                'allowEmpty' => true,
	                'table' => 'scrath_card',
	                'field' => 'id',
	                'where' => array('status = ?' => 0)
	            )),
	            'messages' => array('刮刮卡不存在')
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
	else if ($action == 'ajaxaddress')
	{
	    /* 构造验证器 */
	    $filters = array(
	      
	    );
	    $validators = array(
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
    $controller->data = array_merge($request->getQuery(),$request->getPost());
    
    if ($action == 'ajaxaddress')
    {
        /* 构造验证器 */
        $filters = array(
        );
        $validators = array(

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
   
    
}

/**
 *  检验 ajax
 */
function ajax(&$controller)
{
    $request = $controller->getRequest();
    $op = $request->getQuery('op','');
    $controller->data = $request->getPost();

    if ($op == 'usecard')
    {
        /* 构造验证器 */

        $filters = array(

        );
        $validators = array(

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
    else if ($op == 'address')
    {
        /* 构造验证器 */
        $filters = array(
        );
        $validators = array(
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
    else if ($op == 'verification')
    {
        /* 构造验证器 */
        $filters = array(
        );
        $validators = array(
            'redeem_code' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请输入货号',
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
    else if ($op == 'weixin')
    {
        /* 构造验证器 */
        $filters = array(
        );
        $validators = array(
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


