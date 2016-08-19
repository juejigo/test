<?php

class Core2_Plugin_Autologin extends Zend_Controller_Plugin_Abstract 
{
	/**
	 *  自动登录
	 * 
	 *  检测是否需要登录,登录
	 * 
	 *  @param Zend_Controller_Request_Abstract $request
	 *  @return void
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		
		/* 已登录 */
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()) 
		{
			return ;
		}
		
		/* 登录 */
		if (!empty($_COOKIE['account']) && !empty($_COOKIE['password'])) 
		{
			$config = Zend_Registry::get('config');
			
			/* 验证 */
			$db = Zend_Registry::get('db');
			$account = sanitize($_COOKIE['account']);
			$password = sanitize($_COOKIE['password']);
			
			$adapter = new Zend_Auth_Adapter_DbTable($db,'member','account','password',"? AND status = 1");
			$adapter->setIdentity($account);
			$adapter->setCredential($password);
			$result = $auth->authenticate($adapter);
			
			/* 验证成功 */
			if ($result->isValid()) 
			{
				/* 生成身份证 */
				$resultRowObject = $adapter->getResultRowObject();
				login($resultRowObject->id);
				
				/* 生成下次登录 cookie */
				setcookie('account',$account,SCRIPT_TIME + (60 * 60 * 24 * 7),'/',"{$config->site->domain}");
				setcookie('password',$resultRowObject->password,SCRIPT_TIME + (60 * 60 * 24 * 7),'/',"{$config->site->domain}");
			}
			else 
			{
				setcookie('account','',SCRIPT_TIME - 1,'/',"{$config->site->domain}");
				setcookie('password','',SCRIPT_TIME - 1,'/',"{$config->site->domain}");
			}
		}
	}
}

?>