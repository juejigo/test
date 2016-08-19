
</body>
</html>
<!-- 美恰 -->
  <script type='text/javascript'>
    (function(m, ei, q, i, a, j, s) {
        m[a] = m[a] || function() {
            (m[a].a = m[a].a || []).push(arguments)
        };
        j = ei.createElement(q),
            s = ei.getElementsByTagName(q)[0];
        j.async = true;
        j.charset = 'UTF-8';
        j.src = i + '?v=' + new Date().getUTCDate();
        s.parentNode.insertBefore(j, s);
    })(window, document, 'script', '//static.meiqia.com/dist/meiqia.js', '_MEIQIA');
    _MEIQIA('entId', 25304);
</script>
<!-- 站长统计 -->
<div style="display:none;">
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1260159051'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s11.cnzz.com/z_stat.php%3Fid%3D1260159051 ' type='text/javascript'%3E%3C/script%3E"));</script>
</div>

<!-- 微信分享 -->
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>	
var shareImg = '{$wxImage}';
var sharetitle = '{$wxTitle}';
var sharelink = '{$wxUrl}';
var fxlink = '{$fenxiangUrl}';
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
		link : sharelink,  // 朋友圈分享链接
		imgUrl : shareImg, // 分享图标
		success : function() {
			// 用户确认分享后执行的回调函数
		},
		cancel : function() {
			// 用户取消分享后执行的回调函数
		}
	});
	wx.onMenuShareAppMessage({
		title : sharetitle, // 分享标题
		desc :  sharedesc, // 分享描述
		link :  fxlink, //朋友 分享链接
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

<!--/ 微信分享 -->