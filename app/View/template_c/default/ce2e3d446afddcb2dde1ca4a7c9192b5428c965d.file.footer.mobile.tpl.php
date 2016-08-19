<?php /* Smarty version Smarty-3.1.11, created on 2016-08-18 10:41:13
         compiled from "app\View\template\default\public\fr\footer.mobile.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1338157ac0e8162f233-37349436%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ce2e3d446afddcb2dde1ca4a7c9192b5428c965d' => 
    array (
      0 => 'app\\View\\template\\default\\public\\fr\\footer.mobile.tpl',
      1 => 1471485534,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1338157ac0e8162f233-37349436',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_57ac0e8165e1d3_35704744',
  'variables' => 
  array (
    'wxImage' => 0,
    'wxTitle' => 0,
    'wxUrl' => 0,
    'fenxiangUrl' => 0,
    'wxDescription' => 0,
    'wxSign' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ac0e8165e1d3_35704744')) {function content_57ac0e8165e1d3_35704744($_smarty_tpl) {?>
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
var shareImg = '<?php echo $_smarty_tpl->tpl_vars['wxImage']->value;?>
';
var sharetitle = '<?php echo $_smarty_tpl->tpl_vars['wxTitle']->value;?>
';
var sharelink = '<?php echo $_smarty_tpl->tpl_vars['wxUrl']->value;?>
';
var fxlink = '<?php echo $_smarty_tpl->tpl_vars['fenxiangUrl']->value;?>
';
var sharedesc = '<?php echo $_smarty_tpl->tpl_vars['wxDescription']->value;?>
';

wx.config({
	debug : false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
	appId : '<?php echo $_smarty_tpl->tpl_vars['wxSign']->value['appId'];?>
', // 必填，公众号的唯一标识
	timestamp : '<?php echo $_smarty_tpl->tpl_vars['wxSign']->value['timestamp'];?>
', // 必填，生成签名的时间戳
	nonceStr : '<?php echo $_smarty_tpl->tpl_vars['wxSign']->value['nonceStr'];?>
', // 必填，生成签名的随机串
	signature : '<?php echo $_smarty_tpl->tpl_vars['wxSign']->value['signature'];?>
',// 必填，签名，见附录1
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

<!--/ 微信分享 --><?php }} ?>