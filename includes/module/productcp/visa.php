<?php

/**
 * 检验参数
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
            'page' => 'Int'
        );
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
        return true;
    }
    else if($action == 'edit')
    {
        /* 构造验证器 */
        $filters = array(
            'id' => 'Int'
        );
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
        return true;
    }
    else if ($action == 'delete')
    {
        /* 构造验证器 */
        $filters = array(
            'id' => 'Int'
        );
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
        return true;
    }
    else if ($action == 'add')
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
        return true;
    }
    else if ($action == 'childerlist')
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
        return true;
    }
    else if ($action == 'childeradd')
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
        return true;
    }
    else if ($action == 'childeredit')
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
        return true;
    }
    else if ($action == 'childerdelete')
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
        return true;
    }
}

/**
 *  检验表单
 */
function form(&$controller)
{
    $request = $controller->getRequest();
    $action = strtolower($request->getActionName());
    $controller->data = $request->getPost();

    if ($action == 'add')
    {
        /* 构造验证器 */
        $filters = array(
            'content' =>'allowedTags'
        );
        $validators = array(
            'visa_name' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请填写签证名称',
            ),
            'content' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请填写签证详情',
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
    else if ($action == 'edit')
    {
        /* 构造验证器 */
        $filters = array(
            'content' =>'allowedTags',
            'id' => 'Int',
        );
        $validators = array(
            'visa_name' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请填写签证名称',
            ),
            'content' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请填写签证详情',
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
    else if ($action == 'childeredit')
    {
        /* 构造验证器 */
        $filters = array(
            'id' => 'Int',
            'parent_id' => 'Int',
        );
        $validators = array(
            'visa_name' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请填写签证名称',
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
    else if ($action == 'childeradd')
    {
        /* 构造验证器 */
        $filters = array();
        $validators = array(
            'visa_name' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请填写签证名称',
            ),
            'visa_name' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请填写签证名称',
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

?>