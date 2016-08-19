<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">
	<meta name="format-detection" content="telephone=no" />
	<title>微信支付</title>
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}/scrath/cj.min.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}/scrath/style.css">
</head>
<body>
	<!--支付-->
	<form method="post" name="orderInfo">
		<div class="weui_cells_title tr">x1</div>
		<div class="weui_cell">
			<div class="weui_cell_bd weui_cell_primary">支付金额</div>
			<div class="weui_cell_ft"><span class="red">￥{$order_money}</span></div>
		</div>
		<div class="weui_cells_title">支付方式</div>
		<div class="weui_cell">
			<div class="weui_cell_hd"><img src="/static/style/default/image/scrath/wx.jpg" style="width:30px;margin-right:5px;display:block"></div>
			<div class="weui_cell_bd weui_cell_primary">
          <p>微信支付</p>
      </div>
		</div>
		<div class="btn">
			<a href="javascript:;" class="weui_btn weui_btn_primary" onclick="callpay()">{$order_money}元 确认支付</a>
		</div>
	</form>

	<!--支付 /-->
	
		<!--遮罩提示-->
	{if $type == "2"}
	<div class="zz"></div>
	<div class="adrs_from">
		<div class="title">
			<p class="red">您参与刮刮卡活动次数已用完。</p>
		</div>
		<div class="btn">
			<a href="/scrathuc/scrath/index?id={$scrath.id}" class="weui_btn weui_btn_primary">返回</a>
		</div>
	</div>
   {else if $type == "3"}
	<div class="zz"></div>
	<div class="adrs_from">
		<div class="title">
			<p class="red">您已中奖两次，请给其他人留点机会！</p>
		</div>
		<div class="btn">
			<a href="/scrathuc/scrath/index?id={$scrath.id}" class="weui_btn weui_btn_primary">返回</a>
		</div>
	</div>
	   {else if $type == "4"}
	<div class="zz"></div>
	<div class="adrs_from">
		<div class="title">
			<p class="red">立即分享，可增加一次抽奖机会！</p>
		</div>
		<div class="btn">
			<a href="/scrathuc/scrath/index?id={$scrath.id}" class="weui_btn weui_btn_primary">返回</a>
		</div>
	</div>
	{/if}
	<!--遮罩提示 /-->
	
</body>
</html>

<script>
var order_id = '{$order_id}';
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
			$.post('/scrathuc/scrath/ajax?op=weixin&id='+order_id);
		  window.location.href = '/scrathuc/scrath/scrathcard?order_id={$order_id}';
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
{include file="public/scrath/footer.tpl"}
