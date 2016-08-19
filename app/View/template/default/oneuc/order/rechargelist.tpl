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
	<title>充值记录</title>
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/amazeui.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/fn.mobile.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/order.css">
</head>
<body class="mhome">
  <input type="hidden" id="annListPage" value="1">
  <header data-am-widget="header" class="am-header am-header-default am-header-fixed">
      <div class="am-header-left am-header-nav">
          <a class="mui-icon-back mui-icon go_back" href="javascript:;"></a>
      </div>
      <h3 class="am-header-title">充值记录</h3>
  </header>
  <div class="rechange_table">
    <table>
      <tr>
        <th width="30%">时间</th>
        <th width="40%">交易号</th>
        <th width="30%">金额/状态</th>
      </tr>
      {foreach $rechargeList as $recharge}
      <tr>
        <td>{date("Y-m-d H:i:s",$recharge.dateline)}</td>
        <td>{$recharge.id}</td>
        <td>{$recharge.amount}/{if $recharge.status == 1}已支付{else if $recharge.status == 0}未支付{else if $recharge.status == -1}已关闭{/if}</td>
      </tr>
      {/foreach}
    </table>
  </div>
</body>
</html>
<script src='{$smarty.const.URL_JS}one/vender/jquery.min.js'></script>
<script src='{$smarty.const.URL_JS}one/vender/amazeui.min.js'></script>
<script src="{$smarty.const.URL_JS}one/phase/fn.js"></script>
