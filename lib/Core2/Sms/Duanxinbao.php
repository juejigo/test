<?php

class Core2_Sms_Duanxinbao
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
	private $_interface = 'Duanxinbao';
	
	/**
	 *  构造
	 */
	public function __construct()
	{
		$this->_config = Zend_Registry::get('config')->sms->duanxinbao;
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
	 *  发送短信
	 */
	public function send($mobiles,$content)
	{
		if (is_array($mobiles)) 
		{
			$mobiles = implode(',',$mobiles);
		}
		
		$this->_client->setUri('http://api.smsbao.com/sms');
		$this->_client->setParameterGet(array(
			'u' => $this->_id,
			'p' => $this->_password,
			'm' => $mobiles,
			'c' => $content
		));
		
		$response = $this->_client->request(Zend_Http_Client::POST);
		$result = $response->getBody();
		
		return $this->sendresult($result);
	}
	
	/**
	 *  发送验证码
	 */
	public function sendcode($mobile,$code)
	{
		$this->_client->setUri('http://api.smsbao.com/sms');
		$this->_client->setParameterGet(array(
			'u' => $this->_id,
			'p' => $this->_password,
			'm' => $mobile,
			'c' => sprintf($this->_config->codeTpl,$code,'30分钟')
		));
		
		$response = $this->_client->request(Zend_Http_Client::POST);
		$result = $response->getBody();
		
		return $this->sendresult($result);
	}
	
	/**
	 *  发送结果
	 */
	public function sendresult($result)
	{
		if ($result != 0) 
		{
			return array('errno' => 1,'errmsg' => '发送失败');
		}
		
		return array('errno' => 0);
	}
	
	/**
	 *  查询帐户余额
	 */
	public function getBalance()
	{
		$this->_client->setUri('http://www.smsbao.com/query');
		$this->_client->setParameterPost(array(
			'u' => $this->_id,
			'p' => $this->_password,
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