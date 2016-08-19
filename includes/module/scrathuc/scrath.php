<?php 
/**
 *  检验参数
 */
function params(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->params = array_merge($request->getQuery(),$request->getUserParams());
	if ($action == 'index')
	{
	    /* 构造验证器 */
	    $filters = array(
	        'id' => 'Int',
	         
	    );
	    $validators = array(
	        'id' => array(
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
	else if ($action == 'pay') 
	{
		/* 构造验证器 */
		$filters = array(
			'id' => 'Int',
		   
		);
		$validators = array(
			'id' => array(
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
	else if ($action == 'scrathcard') 
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
	else  if ($action == 'ajaxmoney')
	{
	    /* 构造验证器 */
	    $filters = array(
	        'id' => 'Int',
	         
	    );
	    $validators = array(
	        'id' => array(
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
	else  if ($action == 'ajaxpay')
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
    
    if ($action == 'pay')
    {
        /* 构造验证器 */
        $filters = array(
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
    else  if ($action == 'ajaxpay')
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
    else  if ($action == 'ajaxmoney')
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
    else  if ($action == 'scrathcard')
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

    if ($op == 'money')
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
    else if ($op == 'pay')
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
    else if ($op == 'shareweixin')
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
