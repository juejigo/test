<?php

class Wxapi_UserController extends Core2_Controller_Action_Api  
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();

		$this->models['member'] = new Model_Member();
		$this->models['member_profile'] = new Model_MemberProfile();
	}

	/**
	 *  取得授权信息
	 */
    public function infoAction()
    {
    	if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		//include "lib/api/wxwebpay/lib/WxPay.Api.php";
		//include "lib/api/wxwebpay/unit/WxPay.JsApiPay.php";
		
		
		/* 第一步:获取 access_token */
		
		$client = new Zend_Http_Client(
			null,
			array(
				'adapter' => 'Zend_Http_Client_Adapter_Curl',
				'curloptions' => array(
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_SSL_VERIFYHOST => false))
		);
		
		$client->setUri('https://api.weixin.qq.com/sns/oauth2/access_token');
		$client->setParameterGet(array(
			'appid' => $this->_config->wxapp->appid,
			'secret' => $this->_config->wxapp->secret,
			'code' => $this->input->code,
			'grant_type' => 'authorization_code'
		));
		$response = $client->request(Zend_Http_Client::GET);
		$result = Zend_Json::decode($response->getBody());
		
		if (empty($result['access_token'])) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = '信息获取失败';
			$this->_helper->json($this->json);
		}
		
		/* 第二步:获取用户信息 */
		
		$openid = $result['openid'];
		$client->setUri("https://api.weixin.qq.com/sns/userinfo");
		$client->setParameterGet(array(
			'access_token' => $result['access_token'],
			'openid' => $openid,
			'lang' => 'zh_CN',
		));
		$response = $client->request(Zend_Http_Client::GET);
		$result = Zend_Json::decode($response->getBody());
		
		$this->rows['member'] =  $this->models['member']->fetchRow(
			$this->models['member']->select()
				->where('openid = ?',$openid)
		);
		
		/* 未注册 */
		
		if (empty($this->rows['member'])) 
		{
			$referee = getReferee();
			
			$this->rows['member'] = $this->models['member']->createRow(array(
				'role' => 'member',
				'group' => '0',
				'referee_id' => empty($referee) ? 0 : $referee['id'],
				'openid' => $openid,
				'unionid' => $result['unionid'],
				'status' => 1,
			));
		}
		
		$this->rows['member']->wx_auth = 1;
		$this->rows['member']->save();
		
		$regionInfo = regionInfo($result['province'],$result['city']);
		$this->rows['member_profile'] = $this->models['member_profile']->find($this->rows['member']->id)->current();
		$this->rows['member_profile']->member_name = $result['nickname'];
		$this->rows['member_profile']->sex = $result['sex'];
		$this->rows['member_profile']->avatar = $result['headimgurl'];
		$this->rows['member_profile']->province_id = $regionInfo['province_id'];
		$this->rows['member_profile']->city_id = $regionInfo['city_id'];
		$this->rows['member_profile']->save();
		
		/* 返回 */
		
		$sessionId = applogin($this->rows['member']->id);
		$this->json['sessid'] = $sessionId;
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
    }
}

?>