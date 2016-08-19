<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">
	<title>确认消费</title>
	<link rel="stylesheet" href="{$smarty.const.URL_MIX}v1/css/cj.min.css">
	<link rel="stylesheet" href="{$smarty.const.URL_MIX}v1/css/style.css">
</head>
<body>
	<!--header-->
	<header>
	    <div class="title">确认消费</div>
	</header >
	<div class="top"></div>
	<!--header /-->
	<form action="/orderuc/sn/use" method="post">
	<section class="none">
		<div class="prompt">
			<img src="{$smarty.const.URL_MIX}v1/img/finish.png"> <span>验证码正确，是否消费此券?</span>
		</div>
	</section>
	<section class="use_code">
		<h2>{$orderSn.item_name}</h2>
		<div class="info">价格：<span class="red">￥{$orderSn.price}</span></div>
		<div class="info">验证码：{$orderSn.sn}</div>
		<div class="line"></div>
		<div class="info tc">顾客订单中有 <span id="aAmount" class="red">{$orderSn.available_num}</span> 张消费券</div><!--根据amount值判断可以消费多少张-->
		<div class="info tc">请选择消费的张数</div>
		<div class="amount">
			<div class="min"></div>
			<input type="tel" value="1" id="uAmount"/>
			<div class="add"></div>
		</div>
	</section>
	</form>
	<div class="change_btn">
		<input type="hidden" name="sn" value="{$params.sn}" />
		<button type="button" onclick="code.use()">确认消费</button>
		<a href="/orderuc/sn/verify">返回</a>
	</div>
</body>
</html>

<script src="{$smarty.const.URL_MIX}v1/vender/jquery/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_MIX}v1/vender/jquery/jquery.alerts.js"></script>
<script src="{$smarty.const.URL_MIX}v1/scripts/cj.js"></script>
<script src="{$smarty.const.URL_MIX}v1/scripts/spCode.js"></script>
