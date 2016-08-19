<?php

class Public_PublicController extends Core2_Controller_Action_Fr 
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
	}


	/**
	 *  微信JSAPI
	 */
    public function wxsignAction()
	{
		header("Access-Control-Allow-Origin: ".DOMAIN);
		require_once "lib/api/wxwebpay/jssdk.php";
		require_once "lib/api/wxwebpay/lib/WxPay.Config.php";

		/* 来源地址 */
		
		$url = $_GET['url'];
		$appid = WxPayConfig::APPID;
		$appsecret = WxPayConfig::APPSECRET;
		$jssdk = new JSSDK($appid,$appsecret,$url);
		$signPackage = $jssdk->GetSignPackage();
		echo json_encode($signPackage);exit;
    }
}

?>