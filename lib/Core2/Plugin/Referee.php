<?php

class Core2_Plugin_Referee extends Zend_Controller_Plugin_Abstract 
{
	/**
	 *  用户来源
	 * 
	 *  @param Zend_Controller_Request_Abstract $request
	 *  @return void
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$config = Zend_Registry::get('config');
		
		/* 推荐人 */
		
		$r = $request->getQuery('r');
		if (!empty($r)) 
		{
			Core_Cookie::set('r',$r,60 * 60 * 24 * 7,'/',$config->site->domain);
		}
		
		/* 注册来源 */
		
		$registerFrom = $request->getQuery('register_from');
		if (!empty($registerFrom)) 
		{
			Core_Cookie::set('register_from',$registerFrom,60 * 60 * 24 * 7,'/',$config->site->domain);
		}
	}
}

?>