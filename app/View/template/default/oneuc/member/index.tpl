<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="renderer" content="webkit">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<title>我的</title>
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/amazeui.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/fn.mobile.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/user.css">
</head>
<body class="mhome">
<!--信息栏-->
<div class="user_box">
	<div class="user_info">
		<div class="user_img"><img src="{$memberInfo.avatar}"/></div>
		<div class="am-text-sm">昵称：{$memberInfo.member_name}</div>
		<div class="am-text-sm">友趣游币：{$memberInfo.coin}</div>
	</div>
</div>
<!--信息栏 /-->
<!--列表-->
<ul class="aui-list-view">
	<li class="aui-list-view-cell bd_t"><a href="/oneuc/order/orderlist" class="aui-arrow-right"><img src="{$smarty.const.URL_IMG}one/ddjl.png" >夺宝记录</a></li>
	<li class="aui-list-view-cell bd_t"><a href="/oneuc/order/winlist" class="aui-arrow-right"><img src="{$smarty.const.URL_IMG}one/zjjl.png" >中奖记录</a></li>
	<li class="aui-list-view-cell bd_t"><a href="/oneuc/order/rechargelist" class="aui-arrow-right"><img src="{$smarty.const.URL_IMG}one/czjl.png" >充值记录</a></li>
	<li class="aui-list-view-cell bd_t bd_b"><a href="/oneuc/member/userinfo" class="aui-arrow-right"><img src="{$smarty.const.URL_IMG}one/shdz.png" >完善个人信息</a></li>
</ul>
<!--列表 /-->
<!--底部-->
<div data-am-widget="navbar" class="am-navbar am-cf am-navbar-yungou bd_" id="">
  <ul class="am-navbar-nav am-cf am-avg-sm-4 bd_t">
      <li >
        <a href="/one/phase/list?shaosheng=sb">
            <img src="{$smarty.const.URL_IMG}one/list.png"/>
            <span class="am-navbar-label">列表</span>
        </a>
      </li>
      <li class="active">
        <a href="/oneuc/member/index?shaosheng=sb">
            <img src="{$smarty.const.URL_IMG}one/user2.png"/>
            <span class="am-navbar-label">我的</span>
        </a>
      </li>
  </ul>
</div>
<!--底部 /-->
</body>
</html>
<script src='{$smarty.const.URL_JS}one/vender/jquery.min.js'></script>
<script src='{$smarty.const.URL_JS}one/vender/amazeui.min.js'></script>
