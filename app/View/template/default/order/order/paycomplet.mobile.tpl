<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">
	<title>支付结果</title>
	<link rel="stylesheet" href="{$smarty.const.URL_MIX}v1/css/cj.min.css">
	<link rel="stylesheet" href="{$smarty.const.URL_MIX}v1/css/style.css">
</head>
<body>
	<!--header-->
	<header>
		<span class="go_left"></span>
	    <div class="title">支付结果</div>
	</header >
	<div class="top"></div>
	<!--header /-->
	<!--支付结果-->
	<section class="none">
		<div class="prompt">
			<img src="{$smarty.const.URL_MIX}v1/img/finish.png"> 恭喜您，购买成功！
		</div>
	</section>
	<div class="blank"></div>
	
	<section class="form_view">
	{foreach $items as $item}
		<div class="input_box">
			<label class="gray1">产品名称</label>
			<div class="info">{$item.item_name}</div>
		</div>
		<div class="input_box">
			<label class="gray1">数量</label>
			<div class="info">{$item.num}张</div>
		</div>
	{/foreach}
	</section>
	<div class="blank"></div>
	
	<section class="form_view">
		<!--<div class="input_box">
			<label class="gray1">取票人</label>
			<div class="info">邵钒淳</div>
		</div>-->
		<div class="input_box">
			<label class="gray1">手机号</label>
			<div class="info">{$order.mobile}</div>
		</div>
		<!--<div class="input_box">
			<label class="gray1">证件号</label>
			<div class="info">3303021988*****232</div>
		</div>-->
	</section>
	<div class="input_btn">
        <a href="/orderuc/order/detail?id={$order.id}">查看订单详情</a>
    </div>
	<!--支付结果 /-->
	<div class="end"></div>
</body>
</html>
<script src="{$smarty.const.URL_MIX}v1/vender/jquery/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_MIX}v1/vender/jquery/jquery.alerts.js"></script>
<script src="{$smarty.const.URL_MIX}v1/scripts/cj.js"></script>
<script src="{$smarty.const.URL_MIX}v1/scripts/order.js"></script>