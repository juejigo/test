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
	<title>验证</title>
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}/scrath/cj.min.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}/scrath/style.css">
</head>
<body>
  <!--验证-->
  <section class="validate_box">
    <h2>验证码</h2>
    <input type="tel" id="num" placeholder="请输入客户的验证码">
  </section>
  <div class="btn">
    <a href="javascript:;" class="weui_btn weui_btn_primary" onclick="validate()">验证</a>
  </div>
  <!--验证 /-->
  <!--历史记录-->
  <dl class="vd_list">
    <dt>历史记录</dt>
		{foreach $select as $i => $prize}
    <dd>{date("Y-m-d H:i",$prize.use_time)}       <span class="green">{$prize.member_name} {$prize.redeem_code}</span> 通过验证</dd>
    			{/foreach}
  </dl>
  <!--历史记录 /-->
</body>
</html>
<script src="{$smarty.const.URL_JS}/scrath/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_JS}/scrath/lottery.js"></script>
