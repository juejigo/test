<?php

require_once('includes/function/location.php');

/**
 *  检验参数
 */
function params(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->params = $request->getQuery();
	
	if ($action == 'confirm') 
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

		return items($controller);
	}
	else if ($action == 'create') 
	{
		/* 构造验证器 */
		
		$filters = array(
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

	
	return false;
}

/**
 *  检验类
 */
function items(&$controller)
{
    $data = $controller->params;

    if (!empty($data['items']))
    {
        /* 完整性检验 */

        if (!is_array($data['items']))
        {
            $controller->error->add('items','表单错误');
            return false;
        }

        /* 验证器检验 */

        $items = array();
        foreach ($data['items'] as $item)
        {
            /* 构造验证器 */
            $filters = array(
                'id' => 'Int',
            );
            $validators = array(
                'id' => array(
                    'presence' => 'required',
                    'allowEmpty' => false,
                    'notEmptyMessage' => '请选择产品',
                ),
                'is_adult' => array(
                    'presence' => 'required',
                    'allowEmpty' => false,
                    'notEmptyMessage' => '请选择大人还是小孩',
                ),
                'num' => array(
                    'presence' => 'required',
                    'allowEmpty' => false,
                    'notEmptyMessage' => '请选择产品数量',
                ),
            );
            $input = new Core_Filter_Input($filters,$validators,$item);
            	
            /* 验证器检验 */
            if (!$input->isValid())
            {
                $controller->error->import($input->getMessages());
            }
            	
            if ($controller->error->hasError())
            {
                return false;
            }
            	
            $items[] = $input->getEscaped();
        }
        $data['items'] = $items;

        $controller->params = $data;
    }
    if (!empty($data['room_ids']))
    {
        /* 完整性检验 */

        if (!is_array($data['room_ids']))
        {
            $controller->error->add('room_ids','房间选择');
            return false;
        }

        /* 验证器检验 */

        $items = array();
        foreach ($data['room_ids'] as $item)
        {
            /* 构造验证器 */
            $filters = array(
                'id' => 'Int',
            );
            $validators = array(
                'id' => array(
                    'presence' => 'required',
                    'allowEmpty' => false,
                    'notEmptyMessage' => '请选择产品',
                ),
            );
            $input = new Core_Filter_Input($filters,$validators,$item);

            /* 验证器检验 */
            if (!$input->isValid())
            {
                $controller->error->import($input->getMessages());
            }

            if ($controller->error->hasError())
            {
                return false;
            }

            $items[] = $input->getEscaped();
        }
        $data['room_ids'] = $items;

        $controller->params = $data;
    }

    return true;
}


/**
 *  检验表单
*/
function ajax(&$controller)
{
    $request = $controller->getRequest();
    $op = $request->getQuery('op','');
    $controller->data = $request->getPost();
    
     if ($op == 'create')
    {
        /* 构造验证器 */
        $filters = array();
        $validators = array(
            'tourist_ids' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '旅客ID不能为空',
            ),
            'buyer_name' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '购买人姓名为空',
            ),
            'mobile' => array(
                'presence' => 'required',
                'allowEmpty' => false,
                'notEmptyMessage' => '电话为空',
                'MobileNumber',
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




