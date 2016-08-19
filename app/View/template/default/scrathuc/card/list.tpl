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
	<title>奖品列表</title>
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}/scrath/cj.min.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}/scrath/style.css">
</head>
<body>
{if count($productList)>0}
	<!--奖品列表-->
	<section class="prize_list">
		<ul>
		{foreach $productList as $i => $product}
			<li>
			<a href="/scrathuc/card/prizeinfo?id={$product.prize_id}">
				<div class="prize_img"><img src="{$product.image}" width="100%"></div>
				<div class="info">
				<p>{$product.product_name}</p>
				<h2>验证码：{$product.redeem_code}</h2>
				</div>
				 {if $product.is_deliver==1}
				<i title="已发送"></i>
				 {/if}
				 </a>
			</li>
			{/foreach}
		</ul>
	</section>
	<!--奖品列表 /-->
	{else }
	<!--未中奖-->
	<section class="no_prize_list">
		<img src="/static/style/default/image/scrath/no_price.png">
		<p>亲，您还没有中奖哦~</p>
		<div class="btn">
      <a href="/scrathuc/scrath/index?id={$scrath_id}" class="weui_btn weui_btn_primary">立即参与</a>
    </div>
	</section>
	<!--未中奖 /-->
	{/if}
</body>
</html>
