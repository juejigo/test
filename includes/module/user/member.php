<?php

/**
 *  检验表单
 */
function form(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->data = array_merge($request->getQuery(),$request->getPost());
	
	if ($action == 'profile') 
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
	else if ($action == 'imgupload')
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

    if ($op == 'edit_userinfo')
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
   else  if ($op == 'upload_head')
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
    
?>