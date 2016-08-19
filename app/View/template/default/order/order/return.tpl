{include file='public/fr/header.mobile.tpl'}
<div class="menu">
   <a class="back" href="/order/order/list"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">退款</div>
</div>
<div class="menber">
    <div class="o_list">
       <div class="o_timeinfo">下单时间：{$order.dateline}</div>
	   {foreach $order.items as $i => $item}
       <div class="o_box">
           <div class="o_price">￥{$item.price}<p>x{$item.num}</p></div>
           <div class="o_img"><img src="{$item.image}"></div>
           <div class="o_name"><p>{$item.item_name}</p></div>
       </div>
	   {/foreach}
	   <!--
       <div class="o_box">
           <div class="o_price">￥198<p>x2</p></div>
           <div class="o_img"><img src="images/goods/2.png"></div>
           <div class="o_name"><p>百丽旗下INNET/茵奈儿2015年夏季红色绒布方跟搭扣女凉鞋55388BL5</p></div>
       </div>
	   -->
       <div class="mm_tkje"><em>总计</em>￥{$order.pay_amount}</div>
    </div>
	<FORM METHOD=POST ACTION="" id="order_shipping" onsubmit="return false;">
    <div class="tk_box mgt15">
       <div class="tk_tt">快递公司</div>
       <div class="tk_info"><input type="text" value="请填写快递公司" class="txt_zz" onFocus="if(value =='请填写快递公司'){ value ='' }"onblur="if(value ==''){ value='请填写快递公司' }" name="shipping_company" id="shipping_company"></div>
    </div>
    <div class="tk_box mgt15">
       <div class="tk_tt">快递单号</div>
       <div class="tk_info"><input type="text" value="请填写快递单号" class="txt_zz" onFocus="if(value =='请填写快递单号'){ value ='' }"onblur="if(value ==''){ value='请填写快递单号' }" class="txt_zz" name="shipping_no" id="shipping_no" ></div>
    </div>
    <div class="tk_box mgt15">
       <div class="tk_tt">备注</div>
       <div class="tk_info"><input type="text" name="memo" id="memo" value="" class="txt_zz"></div>
    </div>
    <div class="tk_box mgt15">
		<INPUT TYPE="hidden" NAME="id" value="{$order.id}">
        <div class="tk_info2"><input type="button" value="提交" class="btn_fk" onclick="send('order_shipping','/order/order/return')"></div>
    </div>
    <div class="tkdz">
      <h3>货物要退到哪里呢？</h3>
      <p>【邮寄地址】：江西省上饶市信州区信江东路夜市南门口AS工厂鞋店</p>
      <p>【收 货 人】：王莹</p>
      <p>【手机号码】：15579378802</p>
    </div>
	</FORM>
</div>
{include file='public/fr/footer.mobile.tpl'}