{include file='public/fr/header.mobile.tpl'}
<div class="menu">
   <a class="back" href="/user/wallet"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">提现</div>
</div>
<script type="text/javascript" src="{$smarty.const.URL_WEB}webapp/js/funds.js"></script>
<FORM METHOD=POST ACTION="/funds/funds/post"  id="transfer">
<div class="menber">
    <div class="tk_box">
       <div class="tk_tt">银行类型</div>
       <div class="tk_info">
			<select name="bank_type">
					<option value="1">工商银行</option>
					<option {if $data.bank_type == 2}selected="selected"{/if} value="2">农业银行</option>
					<option {if $data.bank_type == 3}selected="selected"{/if} value="3">中国银行</option>
					<option {if $data.bank_type == 4}selected="selected"{/if} value="4">建设银行</option>
			</select>
       </div>
    </div>
    <div class="tk_box">
       <div class="tk_tt">卡号</div>
       <div class="tk_info"><input type="text" name="card_no" id="card_no" value="{$data.card_no}" class="txt_zz"></div>
    </div>
    <div class="tk_box">
       <div class="tk_tt">户名</div>
       <div class="tk_info"><input type="text" name="owner_name" id="owner_name" value="{$data.owner_name}" class="txt_zz"></div>
    </div>
    <div class="tk_box">
       <div class="tk_tt">开户行</div>
       <div class="tk_info"><input type="text" name="bod" id="bod" value="{$data.bod}" class="txt_zz"></div>
    </div>
    <div class="tk_box mgt15">
       <div class="tk_tt">提现金额</div>
       <div class="tk_info"><input type="text" name="amount" value="{$data.amount}" class="txt_zz"></div>
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