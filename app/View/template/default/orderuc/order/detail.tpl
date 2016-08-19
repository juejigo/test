<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">
	<title>订单详情</title>
	<link rel="stylesheet" href="{$smarty.const.URL_MIX}v1/css/cj.min.css">
	<link rel="stylesheet" href="{$smarty.const.URL_MIX}v1/css/style.css">
</head>
<body>
	<!--header-->
	<header>
		<span class="go_left"></span>
	    <div class="title">订单详情</div>
	</header >
	<div class="top"></div>
	<!--header /-->
	
	<!--产品信息-->
	{foreach $items as $item}
	<section class="prod_info">
		<a href="prodInfo.html?id=10">
			<img src="{$smarty.const.URL_MIX}v1/img/prod_bigimg.jpg">
			<div class="info">
				<h2>{$item.item_name}</h2>
				<p>数量：{$item.num}</p>
				<p>单价：<span class="price">￥{$item.price}</span></p>
			</div>
		</a>
	</section>
	<section class="hot_spot">
		<ul>
			<li>随时退</li>
			<li>过期退</li>
			<li>需要提前预约</li>
		</ul>		
	</section>
	<!--产品信息 /-->

	<dl class="dl">
		<dt>消费券（1张）{if $order.status == 1}<a href="javascript:;" class="refund" onclick="order.refund({$order.id})">退款</a>{/if}</dt>
		<dd>
			<!--<p>取票人：谢孔侠</p>-->
			<p>消费验证码：{$item.sn}</p>
		</dd>
		<dd>
			<div class="code_img">
				<img src="{$item.qrcode}">
			</div>
		</dd>
	</dl>
	<div class="blank"></div>
	<dl class="dl">
		<dt>退款说明</dt>
		<dd>
			如未取票，请在预约日期后30天有效期内在美团后台申请全额退款。人数变动，取票时告诉工作人员即可，剩余的票可申请退款。取票即代表消费，不支持退款。
		</dd>
	</dl>
	<div class="blank"></div>
	{/foreach}
	
	<!--待付款-->
	(if $order.status == 0}
	<div class="input_btn">
        <a href="orderPay.html">付款</a>
    </div>
    {/if}
	<!--待付款 /-->

	<!--商家信息-->
	<!--<dl class="dl">
		<dt class="i_go"><a href="shopInfo.html?id=1" class="dis">商家信息</a></dt>
		<dd>
			<p class="title">雁荡山森林公园</p>
			<p class="gray1">乐清市雁荡山净明路15号</p>
			<p class="gray1 i_ads">56km</p>
			<a href="#" class="check_map">查看地图</a>
		</dd>
	</dl>
	<div class="blank"></div>-->
	<!--商家信息 /-->
	<!--订单信息-->
	<dl class="dl">
		<dt>订单信息</dt>
		<dd>
			<p>订单号：{$order.id}</p>
			<p>下单时间：{date('Y-m-d H:i:s',$order.dateline)}</p>
			<p>手机号：{$order.mobile}</p>
			<p>总价：￥{$order.amount}</p>
			<p>实付：￥{$order.pay_amount}</p>
		</dd>
	</dl>
	<!--订单信息 /-->
	<div class="end"></div>
</body>
</html>

<script src="{$smarty.const.URL_MIX}v1/vender/jquery/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_MIX}v1/vender/jquery/jquery.alerts.js"></script>
<script src="{$smarty.const.URL_MIX}v1/scripts/cj.js"></script>
<script src="{$smarty.const.URL_MIX}v1/scripts/order.js"></script>
