{include file='public/fr/header.mobile.tpl'}
<div class="menu">
   <a class="back" href="/user/wallet"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">转账</div>
</div>
<script type="text/javascript" src="{$smarty.const.URL_WEB}webapp/js/wallet.js"></script>
<FORM METHOD=POST ACTION="/user/wallet/transfer"  id="transfer">
<div class="menber">
    <div class="tk_box">
       <div class="tk_tt">转账账户</div>
       <div class="tk_info"><input type="text" name="account" id="account" value="" class="txt_zz"></div>
    </div>
    <div class="tk_box">
       <div class="tk_tt">账户确认</div>
       <div class="tk_info"><input type="text" name="qraccount" id="qraccount" value="" class="txt_zz"></div>
    </div>
    <div class="tk_box mgt15">
       <div class="tk_tt">转账金额</div>
       <div class="tk_info"><input type="text" name="amount" value="" class="txt_zz"></div>
    </div>
    <div class="tk_box mgt15">
		<INPUT TYPE="hidden" NAME="password" id="password">
		<!--
        <div class="tk_info2"><input type="button" value="确认" class="btn_fk" onclick="send('transfer','/user/wallet/transfer');"></div>
		-->
        <div class="tk_info2"><input type="button" value="确认" class="btn_fk" onclick="confirm();"></div>
    </div>
</div>
</FORM>
{include file='public/fr/footer.mobile.tpl'}