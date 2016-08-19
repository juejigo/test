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
	<title>商品详情</title>
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/amazeui.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/fn.mobile.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/product.css">
</head>
<body class="mhome am-with-fixed-navbar">
	
	<!--轮播图-->
	<div class="am-slider am-slider-default">
		<a class="mui-icon-back mui-icon prod_go_back" href="javascript:;"></a>
	  <ul class="am-slides">
	  	{foreach $image as $img}
	    <li><img src="{$img}" /></li>
	  	{/foreach}
	  </ul>
	</div>
	<!--轮播图 /-->
	<div class="prod_title">
		{if $phaseInfo.status == 1}<span class="am-badge am-badge-danger am-radius">进行中</span>
        {else if $phaseInfo.status == 2}<span class="am-badge am-badge-danger am-radius">倒计时</span>
        {else if $phaseInfo.status == 3}<span class="am-badge am-badge-success am-radius">已揭晓</span>
        {/if}
        (第{$phaseInfo.no}期){$phaseInfo.product_name}
	</div>
	{if $phaseInfo.status == 1}
	<div class="prod_buy_type m_b_5">
		<div class="title">
			<span>方式1</span>
		</div>
		<div class="info_box flex-wrap">
			<div class="info flex-con">
				<div class="am-text-sm">全价购买</div>
				<div class="am-text-xs am-link-muted">无需等待，直接获得商品！</div>
				<div class="am-text-default am-text-danger">￥{$phaseInfo.product_price}</div>
			</div>
			<div class="btn" style="padding-top: 15px;">
				<a class="am-btn am-btn-danger am-radius" href="/product/product/detail?id={$phaseInfo.product_id}">全价购买</a>
			</div>
		</div>
	</div>
	<div class="prod_buy_type ">
		<div class="title">
			<span>方式2</span>
		</div>
		<div class="info_box flex-wrap">
			<div class="info flex-con">
				<div class="am-text-sm">一元夺宝</div>
				<div class="am-text-xs am-link-muted">只需要{$phaseInfo.price}元就有机会获得商品！</div>
				<div class="am-text-xs am-link-muted">期号：{$phaseInfo.no}</div>
				<div class="am-progress am-progress-striped am-progress-xs am-active">
				  <div class="am-progress-bar am-progress-bar-danger"  style="width: {$phaseInfo.now_num/$phaseInfo.need_num*100}%"></div>
				</div>
				<div class="am-text-xs am-link-muted">总需{$phaseInfo.need_num}人次<span class="am-fr">剩余<span class="am-text-primary">{$phaseInfo.need_num-$phaseInfo.now_num}</span></span></div>
			</div>
			<div class="btn" style="padding-top: 15px;">
				<a class="am-btn am-btn-danger am-radius" href="/oneuc/order/pay?id={$params.id}">立即夺宝</a>
			</div>
		</div>
	</div>
	{else if $phaseInfo.status == 2}
	<!--揭晓倒计时-->
	<div class="countdown_box">
		揭晓倒计时：<span class="countdown" data-time="{$phaseInfo.clock}">计算中...</span>
		<a href="/one/phase/calculte?id={$params.id}">查看计算详情</a>
	</div>
	<!--揭晓倒计时-->
	{else if $phaseInfo.status == 3}
	<!--已揭晓获奖者-->
	<div class="winner_box bd_t">
		<div class="bg">
			<div class="jl"></div>
			<div class="info flex-wrap">
				<img src="{$prizeInfo.avatar}">
				<div class="flex-con">
					<p class="am-text-sm">获奖者：{$prizeInfo.member_name}</p>
					<p>ip地址：{$prizeInfo.register_ip}</p>
					<p>本期参与：<span class="zcolor">{$prizeInfo.num}</span>人次</p>
					<p>揭晓时间：{date("Y-m-d H:i:s",$prizeInfo.lottery_time)}</p>
				</div>
			</div>
			<div class="luck_box">
				幸运号码：{$prizeInfo.lucky_num}
				<a href="/one/phase/calculte?id={$params.id}">查看计算详情</a>
			</div>
		</div>
	</div>
	<!--已揭晓获奖者-->
	{/if}
	<ul class="aui-list-view">
		<li class="aui-list-view-cell bd_t"><a href="/product/product/detail?id={$phaseInfo.product_id}" class="aui-arrow-right"><img src="{$smarty.const.URL_IMG}/one/twxq.png" >图文详情<span class="aui-right-name">建议在WiFi下查看</span></a></li>
		<li class="aui-list-view-cell bd_t"><a href="/one/phase/winrecord?id={$phaseInfo.product_id}" class="aui-arrow-right"><img src="{$smarty.const.URL_IMG}/one/wqjx.png" >往期揭晓</a></li>
		<li class="aui-list-view-cell bd_t bd_b"><a href="/one/phase/joinrecord?id={$phaseInfo.id}" class="aui-arrow-right"><img src="{$smarty.const.URL_IMG}/one/cyjl.png" >所有参与记录</a></li>
	</ul>

	{if !empty($lastPhase)}
	<!--下一期-->
	<div class="next_box bd_t">
		第{$lastPhase.no}期正在火热进行中…
		<a href="/one/phase/detail?id={$lastPhase.id}">立即前往</a>
	</div>
	{/if}
	<!--下一期 /-->
</body>
</html>
<script src='{$smarty.const.URL_JS}one/vender/jquery.min.js'></script>
<script src='{$smarty.const.URL_JS}one/vender/amazeui.min.js'></script>
<script src="{$smarty.const.URL_JS}one/phase/fn.js"></script>
<script src="{$smarty.const.URL_JS}one/phase/product.js"></script>
