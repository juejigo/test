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
			),
			'status' => array(
					array('InArray',array(0,1,2,'')),
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
	else if ($action == 'add')
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
	else if ($action == 'edit')
	{
		/* 构造验证器 */
	
		$filters = array(
				'id' => 'Int'
		);
		$validators = array(
				'id' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请选择活动',
						array('DbRowExists',array(
								'table' => 'scrath',
								'field' => 'id',
						)),
						'messages' => array('请选择活动'),
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

	if ($action == 'add')
	{
		/* 构造验证器 */

		$filters = array(
				'draw_amount' => 'Float',
				'total_num' => 'Int',
		);
		$validators = array(
				'scrath_name' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入活动名',
				),
    		    'info' => array(
    		        'presence' => 'required',
    		        'allowEmpty' => false,
    		        'notEmptyMessage' => '请输入活动规则',
    		    ),
    		    'info_image' => array(
    		        'presence' => 'required',
    		        'allowEmpty' => false,
    		        'notEmptyMessage' => '请上传图片',
    		       ),
				'image' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请上传图片',
				),
				'product_name' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入奖品名字',
				),
				'product_level' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入奖项等级',
				),
				'stock' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入奖品总数',
				),
				'start_time' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入开始时间',
				),
				'end_time' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入结束时间',
				),
				'content' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请上传描述',
				),
				'draw_amount' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入金额',
						array('GreaterThan','0'),
						'messages' => array('请输入金额'),
				),
				'total_num' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入总数',
						array('GreaterThan','-0.99'),
						'messages' => array('请输入总数'),
						'default' => '0'
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
	elseif ($action == 'edit')
	{
		/* 构造验证器 */

		$filters = array(
				'draw_amount' => 'Float',
				'total_num' => 'Int',
		);
		$validators = array(
				'scrath_name' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入活动名',
				),
    		    'info' => array(
    		        'presence' => 'required',
    		        'allowEmpty' => false,
    		        'notEmptyMessage' => '请输入活动规则',
    		    ),
    		    'info_image' => array(
    		        'presence' => 'required',
    		        'allowEmpty' => false,
    		        'notEmptyMessage' => '请上传图片',
    		    ),
				'image' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请上传图片',
				),
				'product_name' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入奖品名字',
				),
				'product_level' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入奖项等级',
				),
				'stock' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入奖品总数',
				),
		        'surplus' => array(
        		        'presence' => 'required',
        		        'allowEmpty' => false,
        		        'notEmptyMessage' => '请输入奖品剩余数量',
		        ),
				'start_time' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入开始时间',
				),
				'end_time' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入结束时间',
				),
				'draw_amount' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入金额',
						array('GreaterThan','0'),
						'messages' => array('请输入金额'),
				),
				'total_num' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '请输入总数',
						array('GreaterThan','-0.99'),
						'messages' => array('请输入总数'),
						'default' => '0'
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
 *  检验 ajax
 */
function ajax(&$controller)
{
	$request = $controller->getRequest();
	$op = $request->getQuery('op','');
	$controller->data = $request->getPost();

	if ($op == 'up')
	{
		/* 构造验证器 */

		$filters = array(
				'id' => 'Int',
		);
		$validators = array(
				'id' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '活动不允许为空',
						array('DbRowExists',array(
								'table' => 'scrath',
								'field' => 'id',
								'where' => array('status = ?' => 0),
						)),
						'messages' => array('活动不存在'),
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
	}elseif ($op == 'down')
	{
		/* 构造验证器 */

		$filters = array(
				'id' => 'Int',
		);
		$validators = array(
				'id' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '活动不允许为空',
						array('DbRowExists',array(
								'table' => 'scrath',
								'field' => 'id',
								'where' => array('status = ?' => 1),
						)),
						'messages' => array('活动不存在'),
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
	}elseif ($op == 'delete')
	{
		/* 构造验证器 */

		$filters = array(
				'id' => 'Int',
		);
		$validators = array(
				'id' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '活动不允许为空',
						array('DbRowExists',array(
								'table' => 'scrath',
								'field' => 'id',
						)),
						'messages' => array('活动不存在'),
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
 *  验证 feedbacks 数组
 */
function products(&$controller)
{
	$data = $controller->data;

	/* 完整性检验 */

	if (empty($data['feedbacks']) && !is_array($data['feedbacks']))
	{
		$controller->error->add('feedbacks','表单错误');
		return false;
	}

	/* 验证器检验 */

	$feedbacks = array();
	foreach ($data['feedbacks'] as $feedback)
	{
		// 构造验证器

		$filters = array(
				'product_id' => 'Int',
				'grade' => 'Float',
		);
		$validators = array(
				'product_id' => array(
						'presence' => 'required',
						'allowEmpty' => false,
						'notEmptyMessage' => '参数错误',
						array('DbRowExists',array(
								'table' => 'order_item',
								'field' => 'product_id',
								'where' => array('order_id = ?' => $controller->input->id)
						)),
						'messages' => array('订单状态错误'),
				),
				'grade' => array(
						'presence' => 'required',
						array('InArray',array('5','4.5','4','3.5','3','2.5','2','1.5','1','0.5')),
						'default' => '5'
				),
				'content' => array(
						'presence' => 'required',
						'default' => '好评'
				),
		);
		$input = new Core_Filter_Input($filters,$validators,$feedback);

		// 验证器检验

		if (!$input->isValid())
		{
			$controller->error->import($input->getMessages());
			return false;
		}

		$feedbacks[] = $input->getEscaped();
	}
	$data['feedbacks'] = $feedbacks;

	/* 产品不重复且 */

	$productIds = array();
	foreach ($feedbacks as $feedback)
	{
		if (in_array($feedback['product_id'],$productIds))
		{
			$controller->error->add('feedbacks','重复评价');
			return false;
		}
		$productIds[] = $feedback['product_id'];
	}

	$controller->data = $data;
	return true;
}
?>