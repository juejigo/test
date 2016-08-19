<?php

class Wx_UserController extends Core2_Controller_Action_Fr  
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
	 *  手动授权
	 */
	public function authAction()
	{
		/* 转向 */
		
		$url = urldecode(DOMAIN . 'wx/user/info');
		$this->redirect("https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->_config->wx->appid}&redirect_uri={$url}&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect");
		exit;
	}

	/**
	 *  静默授权
	 */
	public function authbaseAction()
	{
		/* 转向 */
		
		$url = urldecode(DOMAIN . 'wx/user/baseinfo');
		$this->redirect("https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->_config->wx->appid}&redirect_uri={$url}&response_type=code&scope=snsapi_base&state=index#wechat_redirect");
		exit;
	}
	
	/**
	 *  直接注册
	 */
	public function baseinfoAction() 
	{
		include "lib/api/wxwebpay/lib/WxPay.Api.php";
		include "lib/api/wxwebpay/unit/WxPay.JsApiPay.php";
		
		if (!params($this)) 
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:wx.closeWindow();',
					'text' => '关闭')
			));
		}
		
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
			'appid' => WxPayConfig::APPID,
			'secret' => WxPayConfig::APPSECRET,
			'code' => $this->paramInput->code,
			'grant_type' => 'authorization_code'
		));
		$response = $client->request(Zend_Http_Client::GET);
		$result = Zend_Json::decode($response->getBody());
		
		if (empty($result['access_token'])) 
		{
			/* 提示 */
			$this->_helper->notice('信息获取失败',$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:wx.closeWindow();',
					'text' => '关闭')
			));
		}
		
		$this->rows['member'] =  $this->models['member']->fetchRow(
			$this->models['member']->select()
				->where('openid = ?',$result['openid'])
		);
		
		/* 第一次 自动注册 */
		if (empty($this->rows['member'])) 
		{
			$referee = $this->_referee();
			$subscribe = $this->_subscribe($openid);
			
			$this->rows['member'] = $this->models['member']->createRow(array(
				'role' => 'member',
				'group' => '0',
				'referee_id' => empty($referee) ? 0 : $referee['id'],
				'openid' => $result['openid'],
				'subscribe' => $subscribe,
				'status' => '1'
			));
			$this->rows['member']->save();
		}
		
		login($this->rows['member']->id);
		
		/* 返回 */
		
		$session = new Zend_Session_Namespace('wx_redirect_url');
		$redirectUrl = !empty($session->url) ? $session->url : DOMAIN;
		$this->_redirect($redirectUrl);
	}

	/**
	 *  取得授权信息
	 */
    public function infoAction()
    {
    	if (!params($this)) 
		{
			/* 提示 */
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:wx.closeWindow();',
					'text' => '关闭')
			));
		}
		
		include "lib/api/wxwebpay/lib/WxPay.Api.php";
		include "lib/api/wxwebpay/unit/WxPay.JsApiPay.php";
		
		/* 获取模块、控制和方法 */
		
		$request = $this->getRequest();
		$action = strtolower($request->getActionName());
		$module = strtolower($request->getModuleName());
		$controller = strtolower($request->getControllerName());
		
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
			'appid' => WxPayConfig::APPID,
			'secret' => WxPayConfig::APPSECRET,
			'code' => $this->paramInput->code,
			'grant_type' => 'authorization_code'
		));
		$response = $client->request(Zend_Http_Client::GET);
		$result = Zend_Json::decode($response->getBody());
		
		if (empty($result['access_token'])) 
		{
			/* 提示 */
			$this->_helper->notice('信息获取失败',$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:wx.closeWindow();',
					'text' => '关闭')
			));
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
			$referee = $this->_referee();
			$subscribe = $this->_subscribe($openid);
			
			$this->rows['member'] = $this->models['member']->createRow(array(
				'role' => 'member',
				'group' => '0',
				'referee_id' => empty($referee) ? 0 : $referee['id'],
				'openid' => $openid,
				'subscribe' => $subscribe,
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
		
		login($this->rows['member']->id);
		
		/* 返回 */
		
		$session = new Zend_Session_Namespace('wx_redirect_url');
		$redirectUrl = !empty($session->url) ? $session->url : DOMAIN;
		$this->_redirect($redirectUrl);
    }

	/**
	 *  推荐人
	 */
	protected function _referee()
	{
		/* 获得推荐人 */
		$base64 = '';
		if($_COOKIE['r'])
		{
			$base64 = Core_Cookie::get('r');
		}
		
		if($this->_request->getQuery('r',''))
		{
			$base64 = $this->_request->getQuery('r');
		}
		
		if ($base64) 
		{
			$referee = base64_decode($base64,true);
			
			/* 推荐人是否存在 */
			
			$member = $this->_db->select()
				->from(array('m' => 'member'))
				->where("m.account = '".$referee."' or m.openid = '".$referee."'")
				->where('m.status = ?',1)
				->query()
				->fetch();
			
			$referee = $member;
		}

		return !empty($referee) ? $referee : array();
	}
	
	/**
	 *  是否关注
	 */
	public function _subscribe($openid)
	{
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
				'appid' => $this->_config->wx->appid,
				'secret' => $this->_config->wx->secret,
				'grant_type' => 'client_credential'
		));
		$response = $client->request(Zend_Http_Client::GET);
		$result = Zend_Json::decode($response->getBody());
	
		if (empty($result['access_token']))
		{
			/* 提示 */
			$this->_helper->notice('信息获取失败',$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:wx.closeWindow();',
					'text' => '关闭')
			));
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
				'access_token' => $result['access_token'],
				'openid' => $openid,
				'lang' => 'zh_CN'
		));
		$response = $client->request(Zend_Http_Client::GET);
		$info = Zend_Json::decode($response->getBody());
		
		if (!isset($info['subscribe']))
		{
			/* 提示 */
			$this->_helper->notice('subscribe获取失败',$this->error->firstMessage(),'error_1',array(
				array(
					'href' => 'javascript:wx.closeWindow();',
					'text' => '关闭')
			));
		}
		
		return $info['subscribe'];
	}
}

?>