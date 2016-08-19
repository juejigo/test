<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="renderer" content="webkit">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<title>微信安全支付</title>
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/amazeui.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/fn.mobile.css">
  	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/order.css">
  	<style>
		.wx_bg { text-align:center;padding:30px 0;}
		.wx_bg img { width:170px;}
		.wx_bg p { color:#000;font-size:18px;margin-top:15px;}
		.wx_btn { border:1px solid #3cb035;background:#3cb035;color:#fff;font-size: 16px;border-radius:6px;}
		.bottom { position:fixed;bottom:30px;left:0;right:0;color:#999;font-size:12px;text-align:center;}
  	</style>
</head>
<body class="mhome" style="background:#EEEEEE;">
	<div class="wx_bg">
		<img src="{$smarty.const.URL_IMG}one/wxpay_bg.png" alt="">
		<p>微信安全支付</p>
	</div>
	<div class="pay_btn">
		<button type="button" class="am-btn am-btn-block wx_btn" onclick="callpay()">确认支付</button>
	</div>
	<div class="bottom">支付安全由中国人民财产保险股份有限公司承保</div>
</body>
</html>
<script src='{$smarty.const.URL_JS}one/vender/jquery.min.js'></script>
<script src='{$smarty.const.URL_JS}one/vender/amazeui.min.js'></script>
<script src="{$smarty.const.URL_JS}one/phase/fn.js"></script>
<script>
function jsApiCall()
{
	WeixinJSBridge.invoke(
		'getBrandWCPayRequest',
		{$jsApiParameters},
		function(res){
			WeixinJSBridge.log(res.err_msg);
			//alert(res.err_code+res.err_desc+res.err_msg);
			if(res.err_msg=='get_brand_wcpay_request:ok'){
		 		window.location.href = '/oneuc/order/orderlist';
			}
		}
	);
}
function callpay()
{
	if (typeof WeixinJSBridge == "undefined"){
		if( document.addEventListener ){
			document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		}else if (document.attachEvent){
			document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
			document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		}
	}else{

		jsApiCall();
	}
}
</script>