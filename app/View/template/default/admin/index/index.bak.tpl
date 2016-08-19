<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$headerTitle}</title>
<meta content="{$headerKeywords}" name="Keywords">
<meta content="{$headerDescription}" name="Description">
<meta content="width=device-width,height=device-height,initial-scale=1,user-scalable=0" name="viewport">
<link type="image/gif" href="/favicon.gif" rel="shortcut icon">
<link type="image/gif" href="/favicon.gif" rel="bookmark">
<script src="{$smarty.const.URL_JS}lib/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_JS}lib/jqueryui.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_JS}public/fr.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_JS}public/{$module}.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_JS}{$module}/{$controller}/{$action}.js"></script>
<!--<script type="text/javascript" src="https://getfirebug.com/firebug-lite-debug.js"></script>-->
<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_CSS}lib/jqueryui/jqueryui.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_CSS}public/fr.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_CSS}public/{$module}.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_CSS}{$module}/{$controller}/{$action}.css" />
</head><body>

<!-- namespace-front -->
<div id="namespace-admincp">

<script type="text/javascript">
$(function()
{
	// 显示验证码
	$(document).on('focusin','#captcha',function()
	{
		if ($('#captcha-img').hasClass('show'))
		{
			var i = Math.round(Math.random()*10000);
			$('#captcha-img').attr('src','/utility/captcha?' + i).removeClass('show');
		}
	});
	
	// 切换验证码
	$(document).on('click','#captcha-img',function()
	{
		var i = Math.round(Math.random()*10000);
		$(this).attr('src','/utility/captcha?' + i);
	});
})
</script>


<div id="bg">

		<div id="login"></div>
		<div id="login-content">
		<form method="post" action="/admin">
				<!--<div id="logo" style="text-align:center;"><img src="{$smarty.const.URL_IMG}admin/logo.png"></div>-->
				<div id="account" class="login-input"><input type="text" name="account" /></div>
				<div id="password" class="login-input"><input type="password" name="password" /></div>
				<div id="captcha" class="login-input" style="width:100px;"><input style="width:100px;" type="text" name="captcha" /> <img id="captcha-img" class="show" title="点击更换验证码" src="{$smarty.const.URL_IMG}public/blank.gif" /></div>
				<div class="login-r"><input class="login" type="submit" value="登录" /></div>
		</form>
		</div>
		
</div>

</div>
<!--/ namespace-admincp -->

</body></html>