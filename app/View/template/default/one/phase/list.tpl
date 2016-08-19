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
	<title>商品列表</title>
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/amazeui.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/fn.mobile.css">
  	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/product.css">
</head>
<body class="mhome">
<!--列表-->
<div class="list_box">
  <ul class="list" id="indexList">

  </ul>
  <div class="list_load"><span class="mui-spinner"></span></div>
</div>
<!--列表 /-->
<!--底部-->
<div data-am-widget="navbar" class="am-navbar am-cf am-navbar-yungou " id="">
  <ul class="am-navbar-nav am-cf am-avg-sm-4 bd_t">
      <li class="active">
        <a href="/one/phase/list">
            <img src="{$smarty.const.URL_IMG}one/list2.png"/>
            <span class="am-navbar-label">列表</span>
        </a>
      </li>

      <li>
        <a href="/oneuc/member/index">
            <img src="{$smarty.const.URL_IMG}one/user.png"/>
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
<script src="{$smarty.const.URL_JS}one/phase/fn.js"></script>
<script src="{$smarty.const.URL_JS}one/phase/product.js"></script>