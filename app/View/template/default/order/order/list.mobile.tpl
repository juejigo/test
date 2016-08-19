{include file='public/fr/header.mobile.tpl'}
<script type="text/javascript" src="{$smarty.const.URL_WEB}webapp/js/order.js"></script>

<div class="menu">
   <a class="back" href="/user/member"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">我的订单</div>
   <a class="nav_kf" href="#"><img src="{$smarty.const.URL_WEB}webapp/images/btn_kf.png"></a>
</div>
<div class="order">
    <div class="my_order2">
       <ul>
          <li><a href="/order/order/list"  {if $status==''}class="cur"{/if} >全部</a></li>
          <li><a href="/order/order/list?status=0" {if $status=='0'}class="cur"{/if} >待付款</a></li>
          <li><a href="/order/order/list?status=1" {if $status==1}class="cur"{/if} >待发货</a></li>
          <li><a href="/order/order/list?status=2" {if $status==2}class="cur"{/if} >待收货</a></li>
          <li><a href="/order/order/list?status=3" {if $status==3}class="cur"{/if} >已完成</a></li>
          <li><a href="/order/order/list?status=11" {if $status==11}class="cur"{/if} >退款</a></li>
       </ul>
    </div>
	{foreach $orderList as $i => $order}
    <div class="o_list">
       <div class="order_num2"><em>{$order.status_name}</em>订单号：{$order.id}</div>
	   {foreach $order.items as $i => $item}
       <div class="o_box">
           <div class="o_price">￥{$item.price}<p>x{$item.num}</p></div>
           <div class="o_img"><img src="{$item.image}"></div>
           <div class="o_name"><p>{$item.item_name}</p></div>
       </div>
	   {/foreach}
       <div class="o_fkinfo">
          共{$order.item_count}件商品 <i>实付</i> <span>￥{$order.pay_amount}</span>
       </div>
       <div class="fk_btnbox">
	   {if $order.status==0}
	   <input type="button" value="取消订单" class="btn_qxdd" onclick="cancel({$order.id});">
	   <input type="button" value="付款" class="btn_fk2" onclick="payment({$order.id});">
	   <input dataid="{$order.id}" type="button" value="代付" class="df btn_fk2">
	   {else if $order.status==1}
	   <input type="button" value="确认收货" class="btn_fk2" onclick="finish({$order.id});">
	   <input type="button" value="退款" class="btn_qxdd" onclick="refund({$order.id});">
	   {else if $order.status==2}
	   <input type="button" value="查看物流" class="btn_qxdd" onclick="shipping({$order.id});">
	   <input type="button" value="确认收货" class="btn_fk2" onclick="finish({$order.id});">
	   {else if $order.status==3}
	   <input type="button" value="删除订单" class="btn_qxdd" onclick="del({$order.id});">
	   {if $order.feedback==0}
	   <input type="button" value="评价订单" class="btn_qxdd" onclick="feedback({$order.id});">
	   {/if}
	   {else if $order.status==11}
	   <input type="button" value="填写退货单" class="btn_qxdd" onclick="return_product({$order.id});">
	   {/if}
	   </div>
    </div>
	{/foreach}
</div>
{include file='public/fr/footer.mobile.tpl'}