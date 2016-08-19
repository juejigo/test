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
	<title>支付结果</title>
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/amazeui.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/fn.mobile.css">
  	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/order.css">
</head>
<body class="mhome">
  <header data-am-widget="header" class="am-header am-header-default">
      <h3 class="am-header-title">支付结果</h3>
  </header>
	<div class="prompt">
		<img src="{$smarty.const.URL_IMG}one/finish.png">
		<h2>支付成功</h2>
		<p>请等待系统为您揭晓</p>
    	<p>3秒后返回商品列表</p>
		<div class="pay_btn">
	    <a href="/oneuc/order/orderlist" class="am-btn am-btn-secondary am-btn-block">查看购买记录</a>
	  </div>
	</div>
</body>
</html>
<script src='{$smarty.const.URL_JS}one/vender/jquery.min.js'></script>
<script src='{$smarty.const.URL_JS}one/vender/amazeui.min.js'></script>
<script src="{$smarty.const.URL_JS}one/phase/fn.js"></script>
<script>
  setTimeout(function(){
    window.location.href="/one/phase/list";    //返回商品列表地址
  },3000)
</script>
