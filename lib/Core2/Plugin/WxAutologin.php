<?php

class Core2_Plugin_WxAutologin extends Zend_Controller_Plugin_Abstract 
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
		return true;
		
		/* 不是微信浏览器 */
		
		if (!isWeixin()) 
		{
			return;
		}
		
		/* 已登录 */
		
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()) 
		{
			return;
		}
		
		$action = strtolower($request->getActionName());
		$module = strtolower($request->getModuleName());
		$controller = strtolower($request->getControllerName());
		
		if ($module == 'wx' && $controller == 'user' && ($action == 'auth' || $action == 'info')) 
		{
			return;
		}
		
		$session = new Zend_Session_Namespace('wx_redirect_url');
		$redirectUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$session->url = $redirectUrl;
		
		header('location:/wx/user/auth');
		exit;
	}
}

?>