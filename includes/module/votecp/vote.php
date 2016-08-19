<?php
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
			'status' => array(
						array('InArray',array(0,-1,1,2,'all')),
						'default' => 'all'
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
	        'id'=>'Int'	
		);
		$validators = array(
				 'id' => array(
						array('DbRowExists',array(
								'table' => 'vote',
								'field' => 'id',
						)),
						'messages' => array('投票信息错误'),
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
		$validators = array(
			'id' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '请选择投票活动',
				array('DbRowExists',array(
					'table' => 'vote',
					'field' => 'id',
				)),
				'messages' => array('信息错误'),
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
					'subscribe'=>'Int'
			);
			$validators = array(
					'votename' => array(
							'presence' => 'required',
							'allowEmpty' => false,
							'notEmptyMessage' => '请输入投票活动名称',							
					),
					
					'subscribe' => array(
							array('InArray',array(0,1)),
							/* 'messages' => array('是否关注填写错误,请确认'), */
					),
					
					'allow_post' => array(
							array('InArray',array(0,1)),
							/* 'messages' => array('是否可以报名填写错误,请确认'), */
					),
					
					'player_auth' => array(
							array('InArray',array(0,1)),
							/* 'messages' => array('是否可以报名填写错误,请确认'), */
					),
					'comment_auth' => array(
							array('InArray',array(0,1)),
							/* 'messages' => array('是否可以报名填写错误,请确认'), */
					),
					'day_auth' => array(
							array('InArray',array(0,1)),
					),
					'vote_btn' => array(
							'presence' => 'required',
							'allowEmpty' => false,
							'notEmptyMessage' => '请输入活动按钮名',
					),
					'image' => array(
							'presence' => 'required',
							'allowEmpty' => false,
							'notEmptyMessage' => '请上传活动横幅',
					),
					
					'signup_begin_time' => array(
							'presence' => 'required',
							'Date',
							'allowEmpty' => false,
							'notEmptyMessage' => '请输入报名开始时间和结束时间',
					),
					
					'signup_end_time' => array(
							'presence' => 'required',
							'Date',
							'allowEmpty' => false,
							'notEmptyMessage' => '请输入报名开始时间和结束时间',
					),
					
					'vote_begin_time' => array(
							'presence' => 'required',
							'Date',
							'allowEmpty' => false,
							'notEmptyMessage' => '请输入投票开始时间和结束时间',
					),
					
					'vote_end_time' => array(
							'presence' => 'required',
							'Date',
							'allowEmpty' => false,
							'notEmptyMessage' => '请输入投票开始时间和结束时间',
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
					'subscribe'=>'Int'
			);
			$validators = array(					
					'votename' => array(
							'presence' => 'required',
							'allowEmpty' => false,
							'notEmptyMessage' => '请输入投票活动名称',
					),
					'subscribe' => array(
							array('InArray',array(0,1)),
							/* 'messages' => array('是否关注填写错误,请确认'), */
					),
					'allow_post' => array(
							array('InArray',array(0,1)),
							/* 'messages' => array('是否可以报名填写错误,请确认'), */
					),
					'player_auth' => array(
							array('InArray',array(0,1)),
							/* 'messages' => array('是否可以报名填写错误,请确认'), */
					),
					'comment_auth' => array(
							array('InArray',array(0,1)),
							/* 'messages' => array('是否可以报名填写错误,请确认'), */
					),
					'day_auth' => array(
							array('InArray',array(0,1)),
					),
					'vote_btn' => array(
							'presence' => 'required',
							'allowEmpty' => false,
							'notEmptyMessage' => '请输入活动按钮名',
					),
					'image' => array(
							'presence' => 'required',
							'allowEmpty' => false,
							'notEmptyMessage' => '请上传活动横幅',
					),
					'signup_begin_time' => array(
							'presence' => 'required',
							'Date',
							'allowEmpty' => false,
							'notEmptyMessage' => '请输入报名开始时间和结束时间',
					),
					'signup_end_time' => array(
							'presence' => 'required',
							'Date',
							'allowEmpty' => false,
							'notEmptyMessage' => '请输入报名开始时间和结束时间',
					),
					'vote_begin_time' => array(
							'presence' => 'required',
							'Date',
							'allowEmpty' => false,
							'notEmptyMessage' => '请输入投票开始时间和结束时间',
					),
					'vote_end_time' => array(
							'presence' => 'required',
							'Date',
							'allowEmpty' => false,
							'notEmptyMessage' => '请输入投票开始时间和结束时间',
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
		else if ($action == 'up')
		{ 
			/* 构造验证器 */
		
			$filters = array(
					'id' => 'Int'
			);
			$validators = array(
					'id' => array(
							'presence' => 'required',
							'allowEmpty' => false,
							'notEmptyMessage' => '请选择投票活动',
							array('DbRowExists',array(
									'table' => 'vote',
									'field' => 'id',
									'where' => array('status IN (?)' => array(0,2,-1))
							)),
							'messages' => array('上架失败'),
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
		else if ($action == 'down')
		{
			/* 构造验证器 */
		
			$filters = array(
					'id' => 'Int'
			);
			$validators = array(
					'id' => array(
							'presence' => 'required',							
							array('DbRowExists',array(
									'table' => 'vote',
									'field' => 'id',
									'where' => array('status = ?' => 1)
							)),
							'messages' => array('下架失败'),
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
		
		else if ($action == 'delete')
		{
			/* 构造验证器 */
		
			$filters = array(
					'id' => 'Int'
			);
			$validators = array(
					'id' => array(
							'presence' => 'required',
							'allowEmpty' => false,
							'notEmptyMessage' => '请选择投票活动',
							array('DbRowExists',array(
									'table' => 'vote',
									'field' => 'id',
									'where' => array('status = ?' => 1)
							)),
							'messages' => array('请选择投票活动'),
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
?>