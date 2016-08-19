{include file='public/fr/header.mobile.tpl'}
<script type="text/javascript" src="{$smarty.const.URL_WEB}webapp/js/jquery.raty.js"></script>
<div class="menu">
   <a class="back" href="/order/order/list?status=3"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">评价订单</div>
</div>

<FORM METHOD=POST ACTION="/order/order/feedback" id="feedback" onsubmit="return false;">
<div class="order_over">
	{foreach $items as $i => $item}
    <div class="pj_box">
       <div class="pjb_info">
          <div class="pjb_img"><img src="{$item.image}"></div>
          <div class="pjb_name">
             <p class="pjb_pr">￥{$item.price}</p>
             <p>{$item.item_name}</p>
          </div>
       </div>
       <div class="pjb_box">
           <div class="pjb_tt">满意度评价</div>
           <div class="pjb_star">
              <div class="ico_star" id="sp_{$item.id}"></div>
              <!--<script type="text/javascript"> $('#sp_{$item.id}').raty();</script>-->
           </div>
           <div class="pjb_txt">
			  <INPUT TYPE="hidden" NAME="product_id[]" value="{$item.product_id}">
              <textarea name="content[]" placeholder="您的评价能为其他买家提供参考噢！"></textarea>
           </div>
       </div>
    </div>
	{/foreach}
	<INPUT TYPE="hidden" NAME="id" value="{$orderId}">
    <div class="pj_btn mgt15">
	<!--
	<input type="submit" class="i_btn" value="提交">
	-->

	<input type="button" class="i_btn" value="提交" onclick="send('feedback','/order/order/feedback');">

	</div>
</div>
</FORM>
<script>
//$('#sp_13851').raty();
$('.ico_star').raty({
  path    : '{$smarty.const.URL_WEB}webapp',
  //half         : true,
  scoreName    : 'grade[]',
  score        : 5
  //starOff : 'off.png',
  //starOn  : 'on.png'
});
</script>
{include file='public/fr/footer.mobile.tpl'}