<?php

class Core2_Plugin_AppAutologin extends Zend_Controller_Plugin_Abstract 
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
		$sessionId = sanitize($request->getPost('sessid',$request->getQuery('sessid')));
		
		$model = new Model_AppSession();
		$row = $model->find($sessionId)->current();
		
		if (!empty($row)) 
		{
			login($row->member_id);
		}
	}
}

?>