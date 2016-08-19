
     {include file='public/fr/headeruser.tpl'}
  <!--中间内容-->
  <div class="main">
      <div class="pay_box">
        <h2>订单信息</h2>
        <div class="order_info">
          <div class="order_info_row b_b">
            <div class="left">订单编号：{$order.id}</div>
            <div class="right">下单时间：{date("Y-m-d",$order.dateline)}</div>
          </div>
          <div class="order_info_row b_b">
            <div class="left"><h3>{$order.subject}</h3></div>
            <div class="right"><h4 class="ycolor">￥{$order.pay_amount}</h4></div>
          </div>
          <div class="order_info_row">
            <div class="left">
             {foreach $order.items as $row}
              <p>{$row.product_items}x￥{$row.price}</p>
             {/foreach}
           
              {if  count($order.addon) >0}
              {foreach $order.addon as $row}
              		   <p>{$row.title}x{$row.num}</p>
              {/foreach}
              {/if}
            </div>
            <div class="right">
             {foreach $order.items as $row}
              <p>￥{$row.price*$row.num}</p>
          {/foreach}
              
              
               {if  count($order.addon) >0}
              {foreach $order.addon as $row}
              		<p>￥{$row.price}</p>
              {/foreach}
              {/if}
            </div>
          </div>
        </div>
        <h2>选择支付方式</h2>
        <div class="pay_type_box">
          <div class="pay_type zhifubao" data-type="alipay">
              <span class="pay_radio"></span>
          </div>
        </div>
        <!--hidden-->
        <input type="hidden" id="payType" value="" placeholder="支付方式">
        <input type="hidden" id="orderId" value="{$order.id}" placeholder="订单编号">
        <!--hidden-->
        <button type="button" class="btn btn_ycolor btn_raidus btn_big pay_btn" onclick="pay();" disabled="disabled">确认支付</button>
        <div class="tip">
          <p>• 如果系统或支付平台延时而超出时限，但仍提示付款成功的，最终订单状态请以短信通知为准；</p>
          <p>• 如您支付遇到困难或需要帮助，请致电400-6117-121</p>
        </div>
      </div>
  </div>
  <!--中间内容 /-->
    {include file='public/fr/footer.tpl'}
    <script>
  //提交支付
    function pay(){
      var order=$("#orderId").val(),
          type=$("#payType").val();
      if(!order){
        layer.alert("没有订单号");
        return false;
      }
      if(!type){
        layer.alert("请选择支付方式");
        return false;
      }
      if(type=="alipay"){
    	  window.location.href="/order/order/goalipay?id="+order;
      }

    }

    </script>