<?php

class Core2_Wx
{
	/**
	 *  @var Zend_Config
	 */
	private $_config = null;
	
	/**
	 *  构造
	 */
	public function __construct()
	{
		$this->_config = Zend_Registry::get('config')->wx;
	}
	
	/**
	 *  发送消息模板
	 */
	public function sendMessageTpl($openid,$templateId,$url,$data)
	{
		$accessToken = $this->getToken();
		
		$client = new Zend_Http_Client(
			null,
			array(
				'adapter' => 'Zend_Http_Client_Adapter_Curl',
				'curloptions' => array(
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_SSL_VERIFYHOST => false))
		);
		
		$client->setUri('https://api.weixin.qq.com/cgi-bin/message/template/send');
		$client->setParameterGet(array(
			'access_token' => $accessToken,
		));
		
		$data = array(
			'touser' => $openid,
			'template_id' => $templateId,
			'url' => $url,
			'topcolor' => '#7B68EE',
			'data' => $data
		);
		$data = Zend_Json::encode($data);
		$client->setRawData($data,'json');
		
		$response = $client->request(Zend_Http_Client::POST);
		$result = $response->getBody();
		
		if ($result['errcode'] != 0) 
		{
			return false;
		}
		
		return true;
	}
	
	/**
	 *  获取 token
	 */
	public function getToken()
	{
		/*$wxAccesstokenModel = new Model_WxAccesstoken();
		$wxAccesstokenRow = $wxAccesstokenModel->fetchRow(
			$wxAccesstokenModel->select() 
				->where('member_id = ?',0)
		);*/
		
		$client = new Zend_Http_Client(
			null,
			array(
				'adapter' => 'Zend_Http_Client_Adapter_Curl',
				'curloptions' => array(
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_SSL_VERIFYHOST => false))
		);
		
		$client->setUri('https://api.weixin.qq.com/cgi-bin/token');
		$client->setParameterGet(array(
			'appid' => $this->_config->appid,
			'secret' => $this->_config->secret,
			'grant_type' => 'client_credential'
		));
		$response = $client->request(Zend_Http_Client::GET);
		$result = Zend_Json::decode($response->getBody());
		
		if (empty($result['access_token'])) 
		{
			return false;
		}
		
		return $result['access_token'];
	}
	
	/**
	 *  发送红包
	 */
	public function sendEnvelope($openid,$money,$params)
	{
		$client = new Zend_Http_Client(
			null,
			array(
				'adapter' => 'Zend_Http_Client_Adapter_Curl',
				'curloptions' => array(
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_SSL_VERIFYHOST => false,
					CURLOPT_SSLCERTTYPE => 'PEM',
					CURLOPT_SSLCERT => 'lib/api/wxwebpay/cert/apiclient_cert.pem',
					CURLOPT_SSLKEYTYPE => 'PEM',
					CURLOPT_SSLKEY => 'lib/api/wxwebpay/cert/apiclient_key.pem'
			))
		);
		
		/* 初始化参数 */
		
		$params['nonce_str'] = $this->_noncestr();
		$params['mch_billno'] = $this->_mchBillno();
		$params['mch_id'] = $this->_config->mchid;
		$params['wxappid'] = $this->_config->appid;
		$params['send_name'] = $params['send_name'];
		$params['re_openid'] = $openid;
		$params['total_amount'] = (int) ($money*100);
		$params['total_num'] = 1;
		$params['wishing'] = isset($params['wishing']) ? $params['wishing'] : '';
		$params['client_ip'] = $_SERVER['SERVER_ADDR'];
		$params['act_name'] = isset($params['act_name']) ? $params['act_name'] : '';
		$params['remark'] = isset($params['remark']) ? $params['remark'] : '';
		$params['sign'] = $this->_sign($params);
		
		/* 发送 POST 请求 */
		
		$xml = $this->array2xml($params);
		$client->setUri('https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack');
		$client->setRawData($xml,'text/xml');
		$response = $client->request(Zend_Http_Client::POST);
		
		$xmlObj = simplexml_load_string($body = $response->getBody(),'SimpleXMLElement',LIBXML_NOCDATA);
		
		$db = Zend_Registry::get('db');
		$envelopeModel = new Model_Envelope();
		
		
		$envelopeRow = $envelopeModel->createRow();
		$envelopeRow->mch_billno = $params['mch_billno'];
		$envelopeRow->money = $money;
		
		// 用户ID
		$memberId = $db->select()
			->from(array('m' => 'member'),array('id'))
			->where('m.openid = ?',$openid)
			->query()
			->fetchColumn();
		$envelopeRow->member_id = $memberId;
		
		if ($xmlObj->return_code == 'FAIL' || $xmlObj->result_code == 'FAIL') 
		{
			$envelopeRow->status = 0;
			$envelopeRow->err_msg = $xmlObj->return_msg;
			
			
			return false;
		}
		else if ($xmlObj->return_code == 'SUCCESS' && $xmlObj->result_code == 'SUCCESS')
		{
			$envelopeRow->status = 1;
		}
		
		$envelopeRow->save();
		
		return true;
	}
	
	/**
	 *  获取用户信息
	 */
	public function userinfo($openid)
	{
		$info = array();
		$accessToken = $this->getToken();
		
		if (empty($accessToken)) 
		{
			return false;
		}
		
		$client = new Zend_Http_Client(
			null,
			array(
				'adapter' => 'Zend_Http_Client_Adapter_Curl',
				'curloptions' => array(
						CURLOPT_SSL_VERIFYPEER => false,
						CURLOPT_SSL_VERIFYHOST => false))
		);
		
		$client->setUri('https://api.weixin.qq.com/cgi-bin/user/info');
		$client->setParameterGet(array(
				'access_token' => $accessToken,
				'openid' => $openid,
				'lang' => 'zh_CN'
		));
		$response = $client->request(Zend_Http_Client::GET);
		$info = Zend_Json::decode($response->getBody());
		
		return $info;
	}
	
	/**
	 *  生成 nonceStr
	 */
	public function _noncestr()
	{
		$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';  
		$str ='';
		for ( $i = 0; $i < 32; $i++ )
		{  
			$str .= substr($chars,mt_rand(0,strlen($chars)-1),1);  
		}
		
		return $str;
	}
	
	/**
	 *  生成 sign
	 */
	public function _sign($params)
	{
		// 排序并生成字符串
		$params = array_filter($params);
		ksort($params);
		
		$string = '';
		foreach ($params as $k => $v)
		{
			if($k != 'sign' && $v != '' && !is_array($v)){
				$string .= $k . '=' . $v . '&';
			}
		}
		$string = trim($string, '&');
		
		// 最后拼接上 key
		$string = $string . "&key={$this->_config->key}";
		
		// MD5 加密
		$string = md5($string);
		
		// 所有字符转为大写
		return strtoupper($string);
	}
	
	/**
	 *  生成 mch_billno
	 */
	protected function _mchBillno()
	{
		$db = Zend_Registry::get('db');
		$billno = $this->_config->mchid . date('Ymd');
		$radom = mt_rand(1000000000,9999999999);
		$billno = $billno . $radom;
		
		$count = $db->select()
			->from(array('l' => 'envelope'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('l.mch_billno = ?',$billno)
			->query()
			->fetchColumn();
		
		while ($count != 0)
		{
			return $this->_mchBillno();
		}
		
		return $billno;
	}
	
	/**
	 *  根据数组生成 xml
	 */
	public function array2xml($array)
	{
		$xml = '<xml>';
		foreach ($array as $k => $val) 
		{
			$xml .= "<{$k}><![CDATA[{$val}]]></{$k}>";
		}
		$xml .= '</xml>';
		
		return $xml;
	}
}

?>