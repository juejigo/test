<?php

class Pushapi_JpushController extends Core2_Controller_Action_Api  
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['app_registrationid'] = new Model_AppRegistrationid();
	}
	
	/**
	 *  注册
	 */
	public function registerAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		$this->rows['app_registrationid'] = $this->models['app_registrationid']->find($this->_user->id)->current();
		if (empty($this->rows['app_registrationid'])) 
		{
			$this->rows['app_registrationid'] = $this->models['app_registrationid']->createRow(array(
				'member_id' => $this->_user->id,
				'platform' => $this->input->platform
			));
		}
		
		$this->rows['app_registrationid']->registrationid = $this->input->registrationid;
		$this->rows['app_registrationid']->platform = $this->input->platform;
		$this->rows['app_registrationid']->save();
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  注销
	 */
	public function logoutAction()
	{
		$this->rows['app_registrationid'] = $this->models['app_registrationid']->find($this->_user->id)->current();
		if (!empty($this->rows['app_registrationid']))
		{
			$this->rows['app_registrationid']->delete();
		}
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
}

?>