<?php

class Utility_CaptchaController extends Core2_Controller_Action_Fr 
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
	}
	
	/**
	 *  显示
	 */
	public function indexAction()
	{
		$captcha = new Core_Captcha();
		$captcha->display();
	}
}

?>