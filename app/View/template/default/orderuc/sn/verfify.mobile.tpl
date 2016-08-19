<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">
	<title>验证消费码</title>
	<link rel="stylesheet" href="{$smarty.const.URL_MIX}v1/css/cj.min.css">
	<link rel="stylesheet" href="{$smarty.const.URL_MIX}v1/css/style.css">
</head>
<body>
	<!--header-->
	<header>
		<span class="go_left"></span>
	    <div class="title">验证消费码</div>
	    <div class="header_right">
			<a href="index.html" class="index"></a>
	    </div>
	</header >
	<div class="top"></div>
	<!--header /-->
	<section class="form_view">
		<div class="input_box">
			<div class="padl20">
	            <input type="tel" id="code" name="code" placeholder="请输入消费验证码" class="width100">
	        </div>
		</div>
	</section>
	<div class="input_btn">
        <button type="button" onclick="code.validate();">验证</button>
    </div>
</body>
</html>
<script src="{$smarty.const.URL_MIX}v1/vender/jquery/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_MIX}v1/vender/jquery/jquery.alerts.js"></script>
<script src="{$smarty.const.URL_MIX}v1/scripts/cj.js"></script>
<script src="{$smarty.const.URL_MIX}v1/scripts/spCode.js"></script>
