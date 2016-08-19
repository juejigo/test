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
	<title>支付成功</title>
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}/scrath/cj.min.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}/scrath/style.css">
</head>
<body>
	<!--用户ID-->
	<input type="hidden" id="userId" value="{$user_id}" />
	<input type="hidden" name="order_id" id="order_id"  value="{$order_id}"  />
	<!--支付成功标题-->
	
	<section class="succ_box">
		<img src="/static/style/default/image/scrath/iconfont-ok.png">
		<p class="ts">支付成功</p>
		<p class="money">{$scrath.total_money}元</p>
		<div class="sm">刮刮卡未中奖，微信红包退还{$scrath.total_money}元</div>
	</section>
	<!--支付成功标题 /-->
	<!--刮刮卡-->
	<section class="lottery_box">
		<div class="lottery">
			<!--获取刮奖-->
			<div class="get_before">
				<p class="title">刮开赢取{$scrath.scrath_name}</p>
				<div class="get_btn" onclick="lottery.get(this)">点我刮奖</div>
			</div>
			<!--获取刮奖 /-->
			<div class="get_after" id="getAfter">
				<div class="region">
					<p class="title">恭喜您，获得{$scrath.scrath_name}！</p>
					<a href="/scrathuc/card/index" class="get_btn">查看详情</a>
					<img src="/static/style/default/image/scrath/l-bg.png" id="canvas"/>
				</div>
			</div>
		</div>
		<div class="info">
			<div class="has fl"><a href="/scrathuc/card/index?id={$scrath.scrath_id}">已中奖<span>{$scrathcard.prizecard}</span>次</a></div>
		</div>
		<div class="btn">
			<a href="/scrathuc/scrath/index?id={$scrath.scrath_id}" class="weui_btn weui_btn_primary">再来一次</a>
		</div>
	</section>
	<!--刮刮卡 /-->
	<!--收货地址-->
	<section class="adrs_box" style="display: none;">
		<div class="zz"></div>
		<div class="adrs_from">
			<div class="title">
				<p class="red" >恭喜您中奖了！</p>
				<p class="gary">请填写真实的手机号码以便核实身份。</p>
			</div>
			<div class="input_view">
				<input type="hidden" id="scrath_prize_id" value="" />
				<input type="tel" id="phone" placeholder="手机号码"/>
			</div>
			<div class="btn">
				<a href="javascript:;" class="weui_btn weui_btn_primary" onclick="save()">确认保存</a>
			</div>
		</div>

	</section>
	<!--收货地址 /-->
	 {if $type == "0"}
	<div class="zz"></div>
	<div class="adrs_from">
		<div class="title">
			<p class="red">该订单未支付或发生错误，请联系客服！</p>
		</div>
		<div class="btn">
			<a href="/scrathuc/scrath/index?id={$scrath.scrath_id}" class="weui_btn weui_btn_primary">返回</a>
		</div>
	</div>
	{/if}
</body>
</html>
<script src="{$smarty.const.URL_JS}/scrath/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_JS}/scrath/canvas.js"></script>
<script src="{$smarty.const.URL_JS}/scrath/lottery.js"></script>
{include file="public/scrath/footer.tpl"}
