<?php

class Core2_Controller_Action_Fr extends Core2_Controller_Action_Abstract 
{
	/**
	 *  预处理
	 */
	public function init()
	{
		parent::init();
	}
	
	/**
	 *  派发后处理
	 * 
	 *  模板变量赋值
	 */
	public function postDispatch()
	{
		parent::postDispatch();
		
		/* SEO */
		
		if (!$this->view->engine->getTemplateVars('headerTitle')) 
		{
			$this->view->headerTitle = '友趣游 - 尾单，我们只做正品';
		}
		if (!$this->view->engine->getTemplateVars('headerKeywords')) 
		{
			$this->view->headerKeywords = '友趣游 旅游尾单';
		}
		if (!$this->view->engine->getTemplateVars('headerDescription')) 
		{
			$this->view->headerDescription = '友趣游是一个专门做旅游尾单的网站。';
		}
		
		/* 微信分享 */
		
		require_once "lib/api/wxwebpay/jssdk.php";
		$jssdk = new JSSDK($this->_config->wx->appid,$this->_config->wx->secret,'');
		$signPackage = $jssdk->GetSignPackage();
		$this->view->wxSign = $signPackage;
		
		if (!$this->view->engine->getTemplateVars('wxUrl')) 
		{
			$wxUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$r = '';
			
			if (strpos($wxUrl,'?') === false) 
			{
				$wxUrl .= "?register_from=22";
			}
			else 
			{
				$wxUrl .= "&register_from=22";
			}
			
			if ($this->_auth->hasIdentity()) 
			{
				$r = createR();
			}
			if (strpos($wxUrl,'?') === false) 
			{
				$wxUrl .= "?r={$r}";
			}
			else 
			{
				$wxUrl .= "&r={$r}";
			}
			
			$this->view->wxUrl = $wxUrl;
		}
		if (!$this->view->engine->getTemplateVars('fenxiangUrl')) 
		{
			$fenxiangUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$r = '';
			
			if (strpos($fenxiangUrl,'?') === false) 
			{
				$fenxiangUrl .= "?register_from=21";
			}
			else 
			{
				$fenxiangUrl .= "&register_from=21";
			}
			
			if ($this->_auth->hasIdentity()) 
			{
				$r = createR();
			}
			if (strpos($fenxiangUrl,'?') === false) 
			{
				$fenxiangUrl .= "?r={$r}";
			}
			else 
			{
				$fenxiangUrl .= "&r={$r}";
			}
			
			$this->view->fenxiangUrl = $fenxiangUrl;
		}
		if (!$this->view->engine->getTemplateVars('wxTitle')) 
		{
			$this->view->wxTitle = '旅游好助手，玩转友趣游';
		}
		if (!$this->view->engine->getTemplateVars('wxDescription')) 
		{
			$this->view->wxDescription = '尾单神器友趣游，带你远行乐无忧，好朋友，友趣游';
		}
		if (!$this->view->engine->getTemplateVars('wxImage')) 
		{
			$this->view->wxImage = URL_IMG . 'public/logo_100.png';
		}
		
		/* 底部配置 */
		
		$this->view->site = $this->_config->site->toArray();
	}
}

?>