
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>	
var shareImg = '{$wxImage}';
var sharetitle = '{$wxTitle}';
var sharelink = '{$wxUrl}';
var sharedesc = '{$wxDescription}';

wx.config({
	debug : false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
	appId : '{$wxSign.appId}', // 必填，公众号的唯一标识
	timestamp : '{$wxSign.timestamp}', // 必填，生成签名的时间戳
	nonceStr : '{$wxSign.nonceStr}', // 必填，生成签名的随机串
	signature : '{$wxSign.signature}',// 必填，签名，见附录1
	jsApiList : [ 'onMenuShareTimeline','onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','startRecord','stopRecord','onVoiceRecordEnd','playVoice','pauseVoice','stopVoice','onVoicePlayEnd','uploadVoice','downloadVoice','chooseImage','previewImage','uploadImage','downloadImage','translateVoice','getNetworkType','openLocation','getLocation','hideOptionMenu','showOptionMenu','hideMenuItems','showMenuItems','hideAllNonBaseMenuItem','showAllNonBaseMenuItem','closeWindow','scanQRCode','chooseWXPay','openProductSpecificView','addCard','chooseCard','openCard' ]
});

wx.ready(function(){
	initWeiXin();
});
function initWeiXin(){
	wx.onMenuShareTimeline({
		title : sharetitle, // 分享标题
		link : sharelink, // 分享链接
		imgUrl : shareImg, // 分享图标
		success : function() {
			$.post('/scrathuc/scrath/ajax?op=shareweixin',function(){
			});
		},
		cancel : function() {
			// 用户取消分享后执行的回调函数
		}
	});
	wx.onMenuShareAppMessage({
		title : sharetitle, // 分享标题
		desc :  sharedesc, // 分享描述
		link :  sharelink, // 分享链接
		imgUrl : shareImg, // 分享图标
		type : '', // 分享类型,music、video或link，不填默认为link
		dataUrl : '', // 如果type是music或video，则要提供数据链接，默认为空
		success : function() {
			// 用户确认分享后执行的回调函数
		},
		cancel : function() {
			// 用户取消分享后执行的回调函数
		}
	});
	wx.onMenuShareQQ({
		title : sharetitle, // 分享标题
		desc : sharedesc, // 分享描述
		link : sharelink, // 分享链接
		imgUrl : shareImg, // 分享图标
		success : function() {
			// 用户确认分享后执行的回调函数	
		},
		cancel : function() {
			// 用户取消分享后执行的回调函数
		}
	});
	wx.onMenuShareWeibo({
		title : sharetitle, // 分享标题
		desc :  sharedesc, // 分享描述
		link :  sharelink, // 分享链接
		imgUrl : shareImg, // 分享图标
		success : function() {
			// 用户确认分享后执行的回调函数
		},
		cancel : function() {
			// 用户取消分享后执行的回调函数
		}
	});
}
</script>