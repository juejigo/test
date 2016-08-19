<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">
	<title>注册完成-众游</title>
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}user/account/register.css" />
</head>
<body>
	<header>注册</header>
	<img src="{$smarty.const.URL_IMG}user/regsuccess.jpg" width="100%" />
	<section class="share_box">
		<img src="{$smarty.const.URL_IMG}user/reg2.jpg" width="75%" />
		<h2>邀请好友注册，领取2元红包。</h2>
		<p>朋友越多，红包越多哦，赶紧行动起来~</p>
		<div class="input_btn">
	        <button type="button" id="share">邀请好友</button>
	    </div>
	</section>
	<div class="mask" id="zz" style="display: none;">
		<div class="share_"></div>
	</div>
</body>
</html>
<script src="{$smarty.const.URL_WEB}webapp/js/jquery-1.9.1.min.js"></script>
<script src="{$smarty.const.URL_JS}user/account/reg.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
var shareImg = '{$wxImage}';		// 分享图标
var sharetitle = '{$wxTitle}';		// 分享标题
var sharelink = '{$wxUrl}';		// 分享链接

wx.config({
	debug : false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
	appId : '{$wxSign.appId}', // 必填，公众号的唯一标识
	timestamp : '{$wxSign.timestamp}', // 必填，生成签名的时间戳
	nonceStr : '{$wxSign.nonceStr}', // 必填，生成签名的随机串
	signature : '{$wxSign.signature}',// 必填，签名，见附录1
	jsApiList : ['onMenuShareTimeline']
});

wx.ready(function(){
	initWeiXin();
});
function initWeiXin(){
	//分享到朋友圈
	wx.onMenuShareTimeline({
		title : sharetitle, 
		link : sharelink, 
		imgUrl : shareImg, 
		success : function() {
			// 用户确认分享后执行的回调函数
			$.post("/user/account/isshare").done(function(){
				nAlert("分享成功");
			})
		},
		cancel : function() {
			// 用户取消分享后执行的回调函数
			nAlert("你取消了分享");
		}
	});
}
</script>