<?php

function params(&$controller)
{
	$request = $controller->getRequest();
	$action = strtolower($request->getActionName());
	$controller->params = array_merge($request->getQuery(),$request->getUserParams());
	
	if ($action == 'index') 
	{
		/* 构造检验器 */
		$filters = array(
		);
		$validators = array(
			'signature' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '缺少参数'),
			'timestamp' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '缺少参数'),
			'nonce' => array(
				'presence' => 'required',
				'allowEmpty' => false,
				'notEmptyMessage' => '缺少参数'),
		);
		
		$controller->paramInput = new Core_Filter_Input($filters,$validators,$controller->params);
		
		/* 检验器检验 */
		if (!$controller->paramInput->isValid()) 
		{
			$controller->error->import($controller->paramInput->getMessages());
		}
		
		if ($controller->error->hasError()) 
		{
			return false;
		}
	    
		$config = Zend_Registry::get('config')->wx;
		$token = $config->token;
		
		$tmpArr = array();
		$tmpArr[] = array($token,$controller->paramInput->timestamp,$controller->paramInput->nonce);
		$tmpArr[] = array($token,$controller->paramInput->nonce,$controller->paramInput->timestamp);
		$tmpArr[] = array($controller->paramInput->timestamp,$token,$controller->paramInput->nonce);
		$tmpArr[] = array($controller->paramInput->timestamp,$controller->paramInput->nonce,$token);
		$tmpArr[] = array($controller->paramInput->nonce,$token,$controller->paramInput->timestamp);
		$tmpArr[] = array($controller->paramInput->nonce,$controller->paramInput->timestamp,$token);
		
		foreach ($tmpArr as $arr) 
		{
			$tmpStr = implode($arr);
			$tmpStr = sha1($tmpStr);
			if($tmpStr == $controller->paramInput->signature)
			{
				return true;
			}
		}
	}
	
	return false;
}

?>