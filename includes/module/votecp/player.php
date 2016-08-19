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
			'vp_id' => 'Int',
			'id'=>'Int',
		);		
		$validators = array(
			'page' => array(
				'allowEmpty' => false,
				'notEmptyMessage' => '参数错误',
				array('GreaterThan',0),
				'messages' => array('参数错误'),
				'default' => '1'
			),
			'vp_id' => array(
				array('DbRowExists',array(
					'allowEmpty' => true,
					'table' => 'vote',
					'field' => 'id',					
				)),
					'messages' => array('参数错误'),
			),			
			'status' => array(
							array('InArray',array(0,-1,1,'all')),
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
				'vp_id' => 'Int'
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
						array('DbRowExists',array(
								'allowEmpty' => true,
								'table' => 'vote_player',
								'field' => 'id',	
						)),
						'messages' => array('参数错误'),
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
								'table' => 'vote_player',
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
			);
			$validators = array(
					'name' => array(
							'presence' => 'required',
							'allowEmpty' => false,
							'notEmptyMessage' => '请输入选手姓名',
					), 
					'image' => array(
							'presence' => 'required',
							'allowEmpty' => false,
							'notEmptyMessage' => '请上传选手照片',
					),
					'declaration' => array(
							'presence' => 'required',							
					),
					'introduction' => array(
							'presence' => 'required',							
					), 
					'vote_num' => array(
							'presence' => 'required',							
					),
					'phone' => array(
							'presence' => 'required',								
					), 
					'audit' => array(
							'presence' => 'required',								
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
		if ($action == 'edit')
		{
			/* 构造验证器 */
		
			$filters = array(
						
			);
			$validators = array(
					'name' => array(
							'presence' => 'required',
							'allowEmpty' => false,
							'notEmptyMessage' => '请输入选手姓名',
		
					),
					'image' => array(
							'presence' => 'required',
							'allowEmpty' => false,
							'notEmptyMessage' => '请上传选手照片',
		
					),
					'declaration' => array(
							'presence' => 'required',
					),
					'introduction' => array(
							'presence' => 'required',
					),
					'vote_num' => array(
							'presence' => 'required',
					),
					'phone' => array(
							'presence' => 'required',
					),
					'audit' => array(
							'presence' => 'required',
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
 *  生成短网址
 */
function shorturl($vote_id)
{
	$client = new Zend_Http_Client(null,array(
						'adapter' => 'Zend_Http_Client_Adapter_Curl',
						'curloptions' => array(
						CURLOPT_SSL_VERIFYPEER => false,
   						CURLOPT_SSL_VERIFYHOST => false))
   					);
   	
	$client->setUri('http://api.t.sina.com.cn/short_url/shorten.json?source=31641035');
	$client->setParameterPost(array(
				'url_long' => 'http://zhongyouwl.com/vote/vote?vote_id='."$vote_id",
	));
	$response = $client->request(Zend_Http_Client::POST);
	$result = Zend_Json::decode($response->getBody());
	return $result;
}


?>