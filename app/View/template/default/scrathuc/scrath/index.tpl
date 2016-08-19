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
	<title>活动首页</title>
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}/scrath/cj.min.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}/scrath/style.css">
</head>
<body style="background:#fff;">
  <!--活动图片-->
  <section class="index_img">
    <img src="{$scrath.image}">
    <a href="/scrathuc/card/index?id={$scrath.id}">中奖记录 >></a>
  </section>
  <!--活动图片 /-->
  <!--活动内容规则-->
  <section class="index_content">
		<!--富文本内容-->
    {$scrath.content}
    {$scrath.info}
		<!--富文本内容 /-->
  </section>
  <!--活动内容规则 /-->
  <div class="fixed_bottom">
    <div class="btn">
      <a href="/scrathuc/scrath/pay?id={$scrath.id}" class="weui_btn weui_btn_primary">立即参与</a>
    </div>
  </div>
</body>
</html>
<script src="{$smarty.const.URL_MIX}v1/vender/jquery/jquery-1.11.1.js"></script>
{include file="public/scrath/footer.tpl"}
