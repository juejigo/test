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
	<title>支付订单</title>
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/amazeui.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/fn.mobile.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/order.css">
</head>
<body class="mhome">
	<input type="hidden" id="limit" value="{$phaseInfo.limit_num}">
	<input type="hidden" id="surplus" value="{$phaseInfo.need_num-$phaseInfo.now_num}">
	<input type="hidden" id="price" value="{$phaseInfo.price}">
  <header data-am-widget="header" class="am-header am-header-default">
      <div class="am-header-left am-header-nav">
          <a class="mui-icon-back mui-icon go_back" href="javascript:;"></a>
      </div>
      <h3 class="am-header-title">支付订单</h3>
      <div class="am-header-right am-header-nav reload"><a href="javascript:;"><i class="am-header-icon am-icon-refresh"></i></a></div>
  </header>
  <div class="order_info bd_t bd_b">
    <img src="{$phaseInfo.image}">
    <div class="title">
      <p class="am-text-sm name">{$phaseInfo.product_name}</p>
      <p class="am-text-xs">单价：<span class="zcolor">{$phaseInfo.price} </span>夺宝币</p>
      <p class="am-text-xs">总需{$phaseInfo.need_num}人次，剩余<span class="zcolor">{$phaseInfo.need_num-$phaseInfo.now_num}</span>人次</p>
		  <div class="num_box">参与人次：
          <div class="num_view">
          <a href="javascript:;" id="min" class="min">-</a>
          <input type="tel" id="num" value="1" class="am-modal-prompt-input">
          <a href="javascript:;" id="add" class="add">+</a>
        </div>
      </div>
    </div>
  </div>
  <p class="total">总计：<span class="zcolor">{$phaseInfo.price}</span> 夺宝币</p>
  <div class="pay_type bd_t">
		<div class="type_view bd_b checked" data-type="duobaobi">
      <div class="pay_title_one">
        <p class="am-text-sm name">夺宝币支付<span>（余额：<span class="zcolor">{$balance}</span>夺宝币）</span></p>
      </div>
      <div class="pay_radio"></div>
    </div>
  </div>
  <div class="pay_btn">
    <button type="button" class="am-btn am-btn-secondary am-btn-block" onclick="pay(this)">确认支付</button>
  	<a href="/oneuc/order/recharge" class="am-btn am-btn-primary am-btn-block">充值</a>
  </div>
</body>
</html>
<script src='{$smarty.const.URL_JS}one/vender/jquery.min.js'></script>
<script src='{$smarty.const.URL_JS}one/vender/amazeui.min.js'></script>
<script src="{$smarty.const.URL_JS}one/phase/fn.js"></script>
<script src="{$smarty.const.URL_JS}one/phase/pay.js"></script>
