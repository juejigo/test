<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">
	<title>支付订单</title>
	<link rel="stylesheet" href="{$smarty.const.URL_MIX}v1/css/cj.min.css">
	<link rel="stylesheet" href="{$smarty.const.URL_MIX}v1/css/style.css">
</head>
<body>
	<!--header-->
	<header>
		<span class="go_left"></span>
	    <div class="title">支付订单</div>
	</header >
	<div class="top"></div>
	<!--header /-->
	<!--支付订单-->
	<form method="post" name="orderInfo">
		<!--<section class="prod_title">
			<h2>温州雁荡山步行街醉仙山庄</h2>
			<div class="price">
				<span class="red">￥38</span>
			</div>
		</section>
		<div class="blank"></div>-->
		<section class="form_view">
			<div class="input_box">
				<img src="{$smarty.const.URL_MIX}v1/img/wx.jpg" class="pay_img fl">
				<div class="pay_title fl">
					<p>微信支付</p>
					<p class="gray1 f12">推荐安装微信5.0及以上版本的使用</p>
				</div>
				<div class="pay_radio"></div>
				<input type="radio" name="payType" value="wx">
			</div>
			<!--<div class="input_box">
				<img src="{$smarty.const.URL_MIX}v1/img/bank.jpg" class="pay_img fl">
				<div class="pay_title fl">
					<p>银行卡支付</p>
					<p class="gray1 f12">支持储蓄卡信用卡，无需开通网银</p>
				</div>
				<div class="pay_radio"></div>
				<input type="radio" name="payType" value="bank">
			</div>
			<div class="input_box">
				<img src="{$smarty.const.URL_MIX}v1/img/zfb.jpg" class="pay_img fl">
				<div class="pay_title fl">
					<p>支付宝支付</p>
					<p class="gray1 f12">推荐有支付宝账户的用户使用</p>
				</div>
				<div class="pay_radio"></div>
				<input type="radio" name="payType" value="zfb">
			</div>-->
		</section>
		<div class="input_btn">
	        <button type="button" onclick="order.pay()">确认付款 ￥{$order.pay_amount}</button>
	    </div>
	</form>
	<!--支付订单 /-->
	<div class="end"></div>
</body>
</html>

<script>
var order_id = '{$order.id}';

{if $isWeixin}
function jsApiCall()
{
	WeixinJSBridge.invoke(
		'getBrandWCPayRequest',
		{$jsApiParameters},
		function(res){
			WeixinJSBridge.log(res.err_msg);
			//alert(res.err_code+res.err_desc+res.err_msg);
			if(res.err_msg=='get_brand_wcpay_request:ok'){
			  window.location.href = '/order/order/paycomplet?id={$order.id}';
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
{/if}
</script>
<script src="{$smarty.const.URL_MIX}v1/vender/jquery/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_MIX}v1/vender/jquery/jquery.alerts.js"></script>
<script src="{$smarty.const.URL_MIX}v1/scripts/cj.js"></script>
<script src="{$smarty.const.URL_MIX}v1/scripts/order.js"></script>