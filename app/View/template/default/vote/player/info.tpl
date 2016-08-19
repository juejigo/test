{include file="public/vote/header.tpl"}
<body class="pb_80">
<!--选手基本资料-->
<section class="palyer_det">
	<a href="/vote/vote?vote_id={$voteinfo.id}"><div class="go_back"></div></a>
	<img src="{$myinfo.image}" width="100%"/>
	<div class="info">
		<div class="fl">{$myinfo.player_num}号 {$myinfo.name}<span>距离上一名还差{$myinfo.diff}票</span></div>
		<div class="fr">得票: <b class='num_{$myinfo.id}'>{$myinfo.vote_num}</b> 排名: {$myinfo.rank}</div>
	</div>
	<div class="vote_btn_box">
		<span class="vote_btn" onclick="votesTo({$myinfo.id})">{$voteinfo.vote_btn}</span>
	</div>
</section>
<div class="palyerbg"></div>
<!--选手基本资料 /-->
<!--宣言-->
<section class="palyer_more">
	<h2>参赛宣言</h2>
	<div class="details">
		{$myinfo.declaration}
	</div>
</section>
<!--宣言 /-->
<!--发布评论-->
<seciton class="comment_post">
	<img src="{$memberAvatar}"/>
	<textarea id="comment" placeholder="请输入对此选手的评论。"></textarea>
	<span>发表</span>
</seciton>
<!--发布评论 /-->
<!--评论列表-->
<section class="comment_list">
	<input type="hidden" id="page" value="1" />
	<h2>网友评论</h2>
	<ul id="comLi">
		
	</ul>
	<div class="ajax_btn">
		更多评论（<span id="surplus">0</span>）<i class="down"></i>
	</div>
</section>
<!--评论列表 /-->
<!--评论浮动框-->
<div class="comment_fixed">
	<div class="to_comment">发表评论</div>
	<div class="comment_num" id="sum">0</div>
</div>
<!--评论浮动框 /-->
<!--底部-->
<div class="player_footer">
	<a href="/vote/vote?vote_id={$voteinfo.id}">返回首页</a>
	<span>|</span>
	<a href="tel:4006117121">客服电话：4006-117-121</a>
	<span>|</span>
	<a href="javascript:scroll(0,0)">返回顶部</a>
	<p>©2016众游网络</p>
</div>
<!--底部 /-->
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>	
var shareImg = '{$wxImage}';
shareImg=decodeURIComponent(shareImg);
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
			// 用户确认分享后执行的回调函数
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
</html>
<script src="{$smarty.const.URL_WEB}webapp/js/vender/jquery/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_WEB}webapp/js/cj.js"></script>
<script src="{$smarty.const.URL_WEB}webapp/js/player.js"></script>
<script src="{$smarty.const.URL_WEB}webapp/js/comment.js"></script>