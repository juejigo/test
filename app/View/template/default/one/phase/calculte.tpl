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
	<title>计算结果</title>
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/amazeui.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/fn.mobile.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/product.css">
</head>
<body class="mhome">
	<header data-am-widget="header" class="am-header am-header-default am-header-fixed bd_b">
	  <div class="am-header-left am-header-nav">
	      <a class="mui-icon-back mui-icon go_back" href="javascript:;"></a>
	  </div>
	  <h3 class="am-header-title">计算结果</h3>
	</header>
	<div class="head_container">
	    <p>计算公式</p>
	    <p>（数值A + 数值B）除以商品总人次取余数+10000001</p>
	</div>
	<div class="content_container">
        <div class="row">
            <p>数值A</p>
            <p class="font_grey">= 商品的最后一个号码分配完毕后，将公示截止该时间点本站全部商品的最后50个参与时间，然后将这50个时间的数值进行求和（得出数值A）</p>
            <div id="result_container">
                <div class="result_title">
                    = {$prizeInfo.salt}
                    <label style="float:right; color:#2a99e0; cursor:pointer;" id="showHide" data-show="0">展开↓</label>
                </div>
                <div class="result_content">
                	<table>
                		<tr>
                			<th width="40%">购买时间</th>
                			<th width="20%">转换数据</th>
                			<th width="40%">会员账号</th>
                		</tr>
                		{foreach $orderList as $order}
                		<tr>
                			<td>{date("Y-m-d H:i:s",$order.pay_time)}</td>
                			<td class="zcolor">{date("His",$order.pay_time)}</td>
                			<td>{$order.member_name}</td>
                		</tr>
                		{/foreach}
                	</table>
                </div>  
            </div>
        </div>
        <div class="row">
            <p>数值B</p>
            <p class="font_grey">= 最近一期中国福利彩票“老时时彩”的揭晓结果</p>
            <p class="font_grey">= {$prizeInfo.lottery_num}（第{$prizeInfo.lottery_phase}期）</p>
        </div>
        <div class="row">
            <p>计算结果</p>
            <p class="font_grey">幸运号码&nbsp;&nbsp;<label class="zcolor">{$prizeInfo.lucky_num}</label></p>
        </div>
    </div>
</body>
</html>
<script src='{$smarty.const.URL_JS}one/vender/jquery.min.js'></script>
<script src='{$smarty.const.URL_JS}one/vender/amazeui.min.js'></script>
<script src="{$smarty.const.URL_JS}one/phase/fn.js"></script>
<script src="{$smarty.const.URL_JS}one/phase/product.js"></script>
