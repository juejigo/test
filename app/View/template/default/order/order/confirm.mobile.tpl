{include file='public/fr/header.mobile.tpl'}
<script>
var type = '{if isset($item_id)}2{else}1{/if}';
var item_id = '{$item_id}';
var item_num = '{$item_num}';
</script>
<script type="text/javascript" src="{$smarty.const.URL_WEB}webapp/js/order.js"></script>
<div class="menu">
   <a class="back" href="/cart/cart/list"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">订单详情</div>
</div>
<div class="order">
	{if $confirmData.consignee}
    <div class="adress">
        <a href="/consignee/consignee/list?id={$confirmData.consignee.id}&from_url={$selfUrl|urlencode}"><p class="s_name" value="{$confirmData.consignee.id}"><em>收货人：{$confirmData.consignee.name}</em>{$confirmData.consignee.mobile}</p><p class="s_adress"><span>收货地址：</span><i>{$confirmData.consignee.address}</i></p></a>
    </div>
	{else}
    <div class="adress_no"><a href="/consignee/consignee/add?&from_url={$selfUrl|urlencode}">你还未添加任何地址，请先添加地址</a></div>
	{/if}
    <div class="o_list">
	  {foreach $confirmData.cart_list.products as $i => $product}
       <div class="o_box">
           <div class="o_price">￥{$product.price}</div>
           <div class="o_img"><img src="{$product.image}"></div>
           <div class="o_name"><p>{$product.item_name}</p>数量：{$product.num}</div>
       </div>
	   {/foreach}
       <div class="o_aa">共{$confirmData.cart_list.products|count}件商品，合计：<em>￥{$confirmData.cart_list.amount}</em></div>
    </div>
    <div class="o_bz">
      <div class="bz_info">
           <div class="o_left">备注</div>
           <div class="o_bzr"><input type="text" value="给卖家留言" onFocus="if(value =='给卖家留言'){ value ='' }" class="o_txt" name="memo" id="memo"></div>
      </div>
    </div>

    <div class="o_hb">
         <div class="hb_list" id="dd_hbbb">
            <ul>
			   {foreach $confirmData.coupon as $key=>$coupon}
               <li><a href="javascript:void(0)" onClick="hb({$coupon.coupon_user_id},{$coupon.value});" >{$coupon.memo}；{$coupon.deadline} 前可使用 ：{$coupon.value}元</a></li>
			   {/foreach}
               <li><a href="javascript:void(0)" onClick="hb(0,'');" >不使用红包</a></li>
            </ul>
         </div>
         <a href="javascript:void(0)" onClick="$('#dd_hbbb').toggle();" class="hb"><span id="coupon_user_id" value="0"></span></a>使用红包
    </div>

    <div class="o_qr">合计：{$confirmData.cart_list.amount} 元   <input type="button" value="确认" class="o_ok" onclick="sent();"></div>
</div>
{include file='public/fr/footer.mobile.tpl'}