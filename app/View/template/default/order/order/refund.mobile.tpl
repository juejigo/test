{include file='public/fr/header.mobile.tpl'}
<div class="menu">
   <a class="back" href="/order/order/list"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">退款</div>
</div>
<FORM METHOD=POST ACTION="" onsubmit="return false;" id="refund">
<div class="menber">
    <div class="tk_box">
       <div class="tk_tt">退款金额</div>
       <div class="tk_info"><input type="text" value="￥{$order.pay_amount}" class="txt_fk" readonly></div>
    </div>
    <div class="tk_box">
       <div class="tk_tt">退款原因</div>
       <div class="tk_info"><textarea NAME="refund_reason" id="refund_reason" onclick="$(this).val('');">请输入退款原因</textarea></div>
    </div>
    <div class="tk_box mgt15">
		<INPUT TYPE="hidden" NAME="id" value="{$order.id}">
        <div class="tk_info2"><input type="button" value="提交申请" class="btn_fk" onclick="send('refund','/order/order/refund');"></div>
    </div>
</div>
</FORM>
{include file='public/fr/footer.mobile.tpl'}