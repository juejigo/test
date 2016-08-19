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
	<title>充值</title>
  	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/amazeui.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/fn.mobile.css">
  	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/order.css">
</head>
<body class="mhome">
  <header data-am-widget="header" class="am-header am-header-default">
      <div class="am-header-left am-header-nav">
          <a class="mui-icon-back mui-icon go_back" href="javascript:;"></a>
      </div>
      <h3 class="am-header-title">充值</h3>
  </header>
  <div class="recharge_box">
    <dl>
      	<dt>
			请选择充值金额
			<p>1元=1夺宝币,只能用于夺宝，充值的款项将无法退回</p>
	    </dt>
      <dd>
        <div class="am-g">
         <div class="am-u-sm-4"><span class="rechange_num checked" value="50">50</span></div>
			<div class="am-u-sm-4"><span class="rechange_num" value="100">100</span></div>
		    <div class="am-u-sm-4"><span class="rechange_num" value="200">200</span></div>
         </div>
		 <div class="am-g">
            <div class="am-u-sm-4"><span class="rechange_num" value="500">500</span></div>
			<div class="am-u-sm-4"><span class="rechange_num" value="1000">1000</span></div>
			<div class="am-u-sm-4"><input type="tel" class="rechange_num" placeholder="其他金额" id="money"></div>
        </div>
      </dd>
    </dl>
  </div>
  <div class="pay_type bd_t">
    <div class="type_view bd_b checked" data-type="weixin">
      <img src="{$smarty.const.URL_IMG}one/wx.jpg" class="pay_img">
      <div class="pay_title">
				<p class="am-text-sm name">微信支付</p>
				<p class="am-text-xs gray">推荐已开通微信钱包的用户使用</p>
			</div>
      <div class="pay_radio"></div>
    </div>
  </div>
  <div class="pay_btn">
    <button type="button" class="am-btn am-btn-secondary am-btn-block" onclick="rechange()">充值</button>
  </div>
</body>
</html>
<script src='{$smarty.const.URL_JS}one/vender/jquery.min.js'></script>
<script src='{$smarty.const.URL_JS}one/vender/amazeui.min.js'></script>
<script src="{$smarty.const.URL_JS}one/phase/fn.js"></script>
<script>
var money=50;		//需要支付的金额
/**
 * 切换充值金额
 */
$(document).on("click",".rechange_num",function(){
	var _this=$(this);
	if(!_this.hasClass("checked")){
		$(".recharge_box").find(".rechange_num").removeClass("checked");
		_this.addClass("checked");
		money=parseInt(_this.attr("value"));
	}
})
/**
 * 自定义金额为正整数
 */
$('#money').bind('keyup', function() {
		var val = $(this).val();
		var moneyReg = /^[0-9]*$/;
		if (!moneyReg.test(val) || val==0) {
				$(this).val('1');
		}
		money=parseInt($(this).val());
});
/**
 * 充值
 */
function rechange(){
	if(isNaN(money)){
		nAlert("请输入正确的金额");
		return false;
	}
	//充值操作
	$.post("/oneuc/order/ajax?op=wxpay",{ money:money},function(data){
		if(data.errno==0){
			window.location.href="/oneuc/order/wxpay?id="+data.id;
		}
	},'json')
}
</script>
