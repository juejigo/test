<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">
	<meta name="format-detection" content="telephone=no" />
	<title>友趣游</title>
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}index/index/mui.min.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}index/index/yqy.mobile.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}index/index/index.mobile.css">
</head>
<body>
	<header class="mui-bar mui-bar-nav login">
		<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
		<h1 class="mui-title">注册</h1>
	</header>
	<div class="mui-content">
		<div class="mui-content-padded" style="margin: 15px;">
			<form class="mui-input-group">
				<div class="mui-input-row">
					<label>手机</label>
					<input type="tel" class="mui-input-clear" id="phone" placeholder="请输入手机号码">
				</div>
				<div class="mui-input-row">
					<label>验证码</label>
					<input type="tel" id="code" placeholder="请输入手机验证码">
					<button type="button" class="mui-btn mui-btn-primary send_code" id="open_code">发送验证码</button>
				</div>
				<div class="mui-input-row">
					<label>密码</label>
					<input type="password" class="mui-input-clear" id="password" placeholder="请输入密码">
				</div>
			</form>
		</div>
		<div class="mui-content-padded" style="margin: 15px;">
			<button type="button" class="mui-btn mui-btn-primary mui-btn-block" id="register">注册</button>
		</div>
		<div class="img_code_box mui-input-group">
		<h2>输入图形码</h2>
		<p class="code_img"><img src="/utility/captcha" onclick="this.src='/utility/captcha?'+Math.random()"></p>
		<div class="code_input">
			<input type="text" id="imgCode" placeholder="请输入图片验证码">
		</div>
		<div class="mui-button-row">
			<button type="button" class="mui-btn mui-btn-primary" id="send_code">确认发送</button>&nbsp;&nbsp;
			<button type="button" class="mui-btn" id="close">取消</button>
		</div>
	</div>
	<div class="mui-off-canvas-backdrop" id="zz"></div>
	</div>
</body>
</html>
<script src='{$smarty.const.URL_JS}index/index/jquery.min.js'></script>
<script src='{$smarty.const.URL_JS}index/index/mui.min.js'></script>
<script src='{$smarty.const.URL_JS}index/index/yqy.mobile.js'></script>
<script src='{$smarty.const.URL_JS}user/account/login.mobile.js'></script>
