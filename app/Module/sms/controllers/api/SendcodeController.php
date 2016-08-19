<?php

class Smsapi_SendcodeController extends Core2_Controller_Action_Api  
{
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
		
		$sms = new Core_Sms();
		$result = $sms->sendcode($this->input->mobile,true);
		
		/* 发送不成功 */
		if ($result['errno'] != 0) 
		{
			$this->json['errno'] = $result['errno'];
			$this->json['errmsg'] = $result['errmsg'];
			$this->_helper->json($this->json);
		}
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  找回密码
	 */
	public function findpasswordAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		$sms = new Core_Sms();
		$result = $sms->sendcode($this->input->mobile,true);
		
		/* 发送不成功 */
		if ($result['errno'] != 0) 
		{
			$this->json['errno'] = $result['errno'];
			$this->json['errmsg'] = $result['errmsg'];
			$this->_helper->json($this->json);
		}
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  验证
	 */
	public function findpaypasswordAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		$sms = new Core_Sms();
		$result = $sms->sendcode($this->input->mobile,true);
		
		/* 发送不成功 */
		if ($result['errno'] != 0) 
		{
			$this->json['errno'] = $result['errno'];
			$this->json['errmsg'] = $result['errmsg'];
			$this->_helper->json($this->json);
		}
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  绑定手机验证码
	 */
	public function bindmobileAction()
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		$sms = new Core_Sms();
		$result = $sms->sendcode($this->input->mobile,true);
		
		/* 发送不成功 */
		if ($result['errno'] != 0) 
		{
			$this->json['errno'] = $result['errno'];
			$this->json['errmsg'] = $result['errmsg'];
			$this->_helper->json($this->json);
		}
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
}

?>