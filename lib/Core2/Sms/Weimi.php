<?php

class Core2_Sms_Weimi
{
	/**
	 *  @var Zend_Http_Client
	 */
	protected $_client = null;
	
	/**
	 *  @var Zend_Config_Ini
	 */
	protected $_config = null;
	
	/**
	 *  @var string
	 */
	private $_id = '';
	
	/**
	 *  @var string
	 */
	private $_password = '';
	
	/**
	 *  @var string
	 */
	private $_interface = 'Weimi';
	
	/**
	 *  构造
	 */
	public function __construct()
	{
		$this->_config = Zend_Registry::get('config')->sms->weimi;
		$this->_id = $this->_config->id;
		$this->_password = $this->_config->password;
		
		$this->_client = new Zend_Http_Client(
			null,
			array(
				'maxredirects' => 10,
				'adapter' => 'Zend_Http_Client_Adapter_Curl',
				'curloptions' => array(
					CURLOPT_FOLLOWLOCATION => true))
		);
	}
	
	/**
	 *  发送验证码
	 */
	public function sendcode($mobile,$code)
	{
		$this->_client->setUri('http://api.weimi.cc/2/sms/send.html');
		$this->_client->setParameterPost(array(
			'uid' => $this->_id,
			'pas' => $this->_password,
			'mob' => $mobile,
			'cid' => $this->_config->smscodeCid,
			'p1' => $code,
			'type' => 'json'
		));
		
		$response = $this->_client->request(Zend_Http_Client::POST);
		$result = $response->getBody();
		
		return $this->sendresult($result);
	}
	
	/**
	 *  调用模版发送
	 */
	public function sendTpl($mobile,$cid,$params)
	{
		$this->_client->setUri('http://api.weimi.cc/2/sms/send.html');
		$this->_client->setParameterPost(array(
			'uid' => $this->_id,
			'pas' => $this->_password,
			'mob' => $mobile,
			'cid' => $cid,
			'type' => 'json'
		));
		
		foreach ($params as $i => $p) 
		{
			$this->_client->setParameterPost("p{$i}",$p);
		}
		
		$response = $this->_client->request(Zend_Http_Client::POST);
		$result = $response->getBody();
		
		return $this->sendresult($result);
	}
	
	/**
	 *  发送结果
	 */
	public function sendresult($result)
	{
		$result = Zend_Json::decode($result);
		
		if ($result['code'] != 0) 
		{
			return false;
		}
		
		return true;
	}
	
	/**
	 *  查询帐户余额
	 */
	public function getBalance()
	{
		$this->_client->setUri('http://api.weimi.cc/2/account/balance.html');
		$this->_client->setParameterPost(array(
			'uid' => $this->_id,
			'pas' => $this->_password,
			'type' => 'json'
		));
		
		$response = $this->_client->request(Zend_Http_Client::POST);
		$result = $response->getBody();
		
		return $result;
	}
	
	/**
	 *  短信服务商
	 */
	public function getInterface()
	{
		return $this->_interface;
	}
}

?>