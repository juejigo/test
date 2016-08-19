<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1,user-scalable=no">
<title>二维码扫描</title>
<link rel="stylesheet" rev="stylesheet" href="{$smarty.const.URL_MIX}m2/css/Reset.css">
<link rel="stylesheet" rev="stylesheet" href="{$smarty.const.URL_MIX}m2/css/ewm.css">
<script>document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);</script>
</head>

<style>
.menu
{ position:fixed;bottom:0;width:100%;background-color:rgb(233,233,233);border-top:1px solid #dedede;min-width:300px;max-width:640px; }
</style>

<body>
<div class="view">
    <div class="bg">
        <div class="logo"></div>
        <div class="text">扫门店二维码<br>下单购买</div>
        <div class="img"><img src="{$smarty.const.URL_MIX}m2/images/ewm.png"></div>
    </div>
    <div class="menu">
       <ul>
          <li><a href="/"><i class="home"></i>首页</a></li>
          <li><a href="/user/member"><i class="mem"></i>个人中心</a></li>
          <li><a href="/user/funds/income"><i class="yeji"></i>我的业绩</a></li>
          <li><a href="/user/member/qrcode"><i class="erweima"></i>我的二维码</a></li>
          <li><a href="http://mp.weixin.qq.com/s?__biz=MzA5Njc5MzQ1MA==&mid=401612402&idx=1&sn=9069bbb46df79fbc8ff516786fe4c249#rd"><i class="kefu"></i>客服</a></li>
       </ul>
    </div>
</div>
</body>
</html>

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
$(function(){
	var fx_url = '{$fxUrl}';
	var wx_url = '{$pageUrl}';
	
	var wx_title = "{$wx_title}";
	var wx_link = fx_url;
	var wx_imgUrl = "{$user->avatar}";
	var wx_desc = "五一享城";
	
	//alert(wx_url);
	//微信处理
	$.ajax({
		type:"GET",
		dataType: "json",
		url: "/public/public/wxsign?&url="+encodeURIComponent(wx_url),
		success:function(signPackage){
		  //alert(location.href.split('#')[0]);
		  wx.config({
			//debug: true,
			appId: signPackage.appId,
			timestamp: signPackage.timestamp,
			nonceStr: signPackage.nonceStr,
			signature: signPackage.signature,
			jsApiList: [
				  'checkJsApi',
				  'onMenuShareAppMessage',
				  'onMenuShareTimeline',
				  'onMenuShareQQ',
				  'onMenuShareWeibo'
			  // 所有要调用的 API 都要加到这个列表中
			]
		  });
		  wx.ready(function () {
		  	
			// 在这里调用 API
			//wx.hideOptionMenu();
			
			//分享到朋友圈
			wx.onMenuShareTimeline({
				title: wx_title, // 分享标题
				link: wx_link, // 分享链接
				imgUrl: wx_imgUrl, // 分享图标
				success: function () {
					// 用户确认分享后执行的回调函数
				},
				cancel: function () {
					// 用户取消分享后执行的回调函数
				}
			});
			//分享给朋友
			wx.onMenuShareAppMessage({
				title: wx_title, // 分享标题
				desc: wx_desc, // 分享描述
				link: wx_link, // 分享链接
				imgUrl: wx_imgUrl, // 分享图标
				type: 'link', // 分享类型,music、video或link，不填默认为link
				dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
				success: function () { 
					// 用户确认分享后执行的回调函数
				},
				cancel: function () { 
					// 用户取消分享后执行的回调函数
				}
			});
			wx.onMenuShareQQ({
				title: wx_title, // 分享标题
				desc: wx_desc, // 分享描述
				link: wx_link, // 分享链接
				imgUrl: wx_imgUrl, // 分享图标
				success: function () { 
				   // 用户确认分享后执行的回调函数
				},
				cancel: function () { 
				   // 用户取消分享后执行的回调函数
				}
			});
			wx.onMenuShareWeibo({
				title: wx_title, // 分享标题
				desc: wx_desc, // 分享描述
				link: wx_link, // 分享链接
				imgUrl: wx_imgUrl, // 分享图标
				success: function () { 
				   // 用户确认分享后执行的回调函数
				},
				cancel: function () { 
					// 用户取消分享后执行的回调函数
				}
			});

		  });
		}
	})

});
</script>