<?php

abstract class Core2_Controller_Action_Uc extends Core2_Controller_Action_Abstract 
{
	/**
	 *  预处理
	 */
	public function init()
	{
		parent::init();
	}
	
	/**
	 *  派发后
	 */
	public function postDispatch()
	{
		parent::postDispatch();
		
		require_once('config/seo.php');
		
		/* SEO */
		
		if (!$this->view->engine->getTemplateVars('headerTitle')) 
		{
			$this->view->headerTitle = $defaultSeo['uc']['title'];
		}
		if (!$this->view->engine->getTemplateVars('headerKeywords')) 
		{
			$this->view->headerKeywords = $defaultSeo['uc']['keywords'];
		}
		if (!$this->view->engine->getTemplateVars('headerDescription')) 
		{
			$this->view->headerDescription = $defaultSeo['uc']['description'];
		}
		
		/* 微信分享 */
		
		if (!$this->view->engine->getTemplateVars('wxTitle')) 
		{
			$this->view->wxTitle = $defaultSeo['uc']['title'];
		}
		if (!$this->view->engine->getTemplateVars('wxDescription')) 
		{
			$this->view->wxDescription = $defaultSeo['uc']['description'];
		}
		if (!$this->view->engine->getTemplateVars('wxImg')) 
		{
			$this->view->wxImg = '';
		}
	}
}

?>