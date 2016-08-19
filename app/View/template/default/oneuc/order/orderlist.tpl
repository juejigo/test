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
	<title>订单</title>
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/amazeui.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/fn.mobile.css">
  	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/order.css">
</head>
<body class="mhome">
  <header data-am-widget="header" class="am-header am-header-default am-header-fixed bd_b">
      <div class="am-header-left am-header-nav">
          <a class="mui-icon-back mui-icon" href="/oneuc/member/index"></a>
      </div>
      <h3 class="am-header-title">夺宝记录</h3>
  </header>
  <!--type-->
  <div class="type_nav bd_b" id="typeNav">
    <div class="type flex-wrap">
      <div class="flex-con"><span class="type_btn checked" data-type="all">全部</span></div>
      <div class="flex-con"><span class="type_btn" data-type="1">进行中</span></div>
      <div class="flex-con"><span class="type_btn" data-type="2">待揭晓</span></div>
      <div class="flex-con"><span class="type_btn" data-type="3">已揭晓</span></div>
    </div>
  </div>
  <!--type-->
  <div class="order_list">
    <ul id="orderList">

    </ul>
    <div class="list_load"><span class="mui-spinner"></span></div>
  </div>
	<!--数量框-->
	<div class="am-modal" tabindex="-1" id="numBox">
		<div class="am-modal-dialog">
			<div class="am-modal-hd" id="modalId"></div>
			<div class="am-modal-bd" id="modalArr"></div>
			<div class="am-modal-footer">
				<span class="am-modal-btn">关闭窗口</span>
			</div>
		</div>
	</div>
	<!--数量框 /-->
</body>
</html>
<script src='{$smarty.const.URL_JS}one/vender/jquery.min.js'></script>
<script src='{$smarty.const.URL_JS}one/vender/amazeui.min.js'></script>
<script src="{$smarty.const.URL_JS}one/phase/fn.js"></script>
<script src="{$smarty.const.URL_JS}one/phase/order.js"></script>
