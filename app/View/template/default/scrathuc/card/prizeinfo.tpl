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
	<title>奖品详情</title>
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}/scrath/cj.min.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}/scrath/style.css">
</head>
<body>
	<!--奖品信息-->
  <section class="prize_info">
    <div class="prize_img"><img src="/static/style/default/image/scrath/iconfont-ok.png" width="100%"></div>
    <div class="info">
      <p>恭喜您获得{$scrath.product_name}</p>
      <h2>验证码：<span class="red">{$scrath.redeem_code}</span></h2>
    </div>
    {if $scrath.is_deliver == 1}
    <i title="已发送"></i>
    {/if}
  </section>
	<!--奖品信息 /-->
	 <!--领奖规则-->
  <section class="rule">
    <!--富文本内容-->
    <img src="{$scrath.image}">
{$scrath.info}
    <!--富文本内容 /-->
    <a class="tel_box" href="tel:4006117121" >客服</a>
  </section>
  <!--领奖规则 /-->

</body>
</html>
<script src="{$smarty.const.URL_MIX}v1/vender/jquery/jquery-1.11.1.js"></script>
{include file="public/scrath/footer.tpl"}