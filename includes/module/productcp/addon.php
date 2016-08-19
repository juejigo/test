<?php

require_once('includes/function/product.php');

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
    else if ($action == 'add')
    {
        /* 构造验证器 */
    
        $filters = array(
            'info' => 'allowedTags'
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
    else if ($action == 'edit')
    {
        /* 构造验证器 */
    
        $filters = array(
            'id' => 'Int',
            'info' => 'allowedTags',
        );
        $validators = array(
        	'id' => array(
        		'presence' => 'required',
        		'allowEmpty' => false,
        		'notEmptyMessage' => '保险不允许为空',
        		array('DbRowExists',array(
        			'table' => 'product_addon',
        			'field' => 'id',
        			'where' => array('addon_type = ?' => 0),
        		)),
        		'messages' => array('保险不存在'),
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
}


/**
 *  检验表单
 */
function form(&$controller)
{
    $request = $controller->getRequest();
    $action = strtolower($request->getActionName());
    $controller->data = array_merge($request->getQuery(),$request->getPost());
    
    if ($action == 'add')
    {
        /* 构造验证器 */
        $filters = array();
        $validators = array(
            'title' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请填写保险标题',
            ),
            'type' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请填写类型',
            ),
            'price' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请填写价格',
                array('GreaterThan','-0.99'),
                'messages' => array('请填写价格'),
                'default' => '0'
            ),
            'addon_name' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请填写保险名称',
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
        	'id' => 'Int',
        );
        $validators = array(
            'title' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请先写保险标题',
            ),
            'type' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请先写类型',
            ),
            'price' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请填写价格',
                array('GreaterThan','-0.99'),
                'messages' => array('请填写价格'),
                'default' => '0'
            ),
            'addon_name' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '请填写保险名称',
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
}

/**
 *  检验 ajax
 */
function ajax(&$controller)
{
    $request = $controller->getRequest();
    $op = $request->getQuery('op','');
    $controller->data = $request->getPost();

    if ($op == 'addonadd')
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
}

