<?php

class Appapi_UtilityController extends Core2_Controller_Action_Api  
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['system_bug'] = new Model_SystemBug();
	}
	
	/**
	 *  启动页
	 */
	public function bootpageAction()
	{
		/* 定义推荐位ID */
		
		$positionId1 = 67;
		
		/* 切换条 */
		
		$cacheId = 'appapi_utility_bootpage';
		if ($this->_cache->test($cacheId)) 
		{
			$rs = $this->_cache->load($cacheId);
		}
		else 
		{
		    require_once 'includes/function/position.php';
		    
		    $positionId1 = array('id' => '67','limit' => '5');
		    $rs = decode($positionId1,'app');
		    $this->_cache->save($rs,$cacheId);
		}
		$this->json['position1'] = $rs;
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  最新版本信息
	 */
	public function lastversionAction() 
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		if ($this->input->platform == 'android') 
		{
			$this->json['code'] = 3;
			$this->json['version'] = '1.1.2';
			$this->json['update_url'] = "http://www.youquyou.cc/runtime/version/youquyou_v1.1.2_3_{$this->input->channel}.apk";
		}
		else if ($this->input->platform == 'ios') 
		{
			$this->json['code'] = 1;
			$this->json['version'] = '1.6';
			$this->json['update_url'] = 'https://itunes.apple.com/us/app/you-qu-you/id1118730808?mt=8&uo=4';
		}
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  分享信息接口
	 */
	public function shareAction()
	{
		$this->json['title'] = '友趣游';
		$this->json['url'] = DOMAIN;
		$this->json['img_url'] = DOMAIN . 'static/style/default/image/public/logo.png';
		$this->json['content'] = '友趣游';
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 *  关于我们
	 */
	public function aboutAction()
	{
		/* 微信二维码 */
		
		$this->json['qrcode_wx'] = DOMAIN . "static/style/default/image/public/mp.jpg";
		
		/* 下载二维码 */
		
		$url = DOMAIN . "download";
		$urlEncode = urlencode($url);
		$this->json['qrcode_download'] = DOMAIN . "utility/qrcode?content={$urlEncode}";
		
		/* 免责申明 */
		
		$this->json['declare_text'] = '友趣游不担保APP服务一定能满足用户的要求，也不担保网络服务不会中断，对网络服务的及时性、安全性、准确性也都不担保。

   友趣游不保证为向用户提供便利而设置的外部链接的准确性和完整性，同时，对于该外部链接指向的不由友趣游实际控制是任何网页上的内容，友趣游不承担任何责任。
对于因不可抗力或友趣游不能控制的原因造成的网络服务中断或其他缺陷，友趣游不承担任何责任，但将尽力减少因此而给用户造成的损失和影响。

   本声明适用于中华人民共和国法律，用户和友趣游一致同意服从中华人民共和国人民法律，接受温州市龙湾区人民法院的管辖。如其中任何条款与中华人民共和国法律抵触，则这些条款将完全按法律规定重新解释，而其他条款依旧具有法律效力。

   我们保留随时更改上述免费及其他条例权利。';
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
	
	/**
	 * 错误信息记录
	 */
	public function systembugAction()
	{
	    if (!form($this))
	    {
	        $this->json['errno'] = '1';
	        $this->json['errmsg'] = $this->error->firstMessage();
	        $this->_helper->json($this->json);
	    }
	    

	    $this->rows['system_bug'] = $this->models['system_bug']->createRow(array(
	        'time' => time(),
	        'url' => $this->input->url,
	        'errno' => $this->input->errno,
	        'parameter' => $this->input->parameter,
	        'network' => $this->input->network,
	        'device' => $this->input->device,
	    ));
	    
	    $this->rows['system_bug']->save();
	    
	    $this->json['errno'] = '0';
	    $this->_helper->json($this->json);
	}
	
}

?>