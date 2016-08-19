{include file='public/fr/header.mobile.tpl'}
<script type="text/javascript">
	//alert(window.location.href);
	//调用微信JS api 支付

	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			{$jsApiParameters},
			function(res){
				WeixinJSBridge.log(res.err_msg);
				//alert(res.err_code+res.err_desc+res.err_msg);
				if(res.err_msg=='get_brand_wcpay_request:ok'){
				  window.location.href = '{$smarty.const.DOMAIN}order/order/paycomplet?id={$order_id}';
				}
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
			if( document.addEventListener ){
				document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			}else if (document.attachEvent){
				document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
				document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			}
		}else{
			jsApiCall();
		}
	}

</script>
<div class="menu">
   <a class="back" href="{$smarty.const.DOMAIN}order/order/pay?id={$order.id}"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">微信安全支付</div>
</div>
<div class="menber">
    <div class="paynow">
      <div class="pay_mon">{$smarty.const.SITE_NAME}<p>￥{$order.pay_amount}</p></div>
      <div class="pay_sj">
         <ul>
            <li><em>收款方</em>{$smarty.const.SITE_NAME}</li>
            <li><em>商　品</em></li>
         </ul>
      </div>
    </div>
    <div class="paynowbtn mgt15"><input type="submit" value="立即支付" class="i_btn"></div>
</div>
{include file='public/fr/footer.mobile.tpl'}