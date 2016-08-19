{include file='public/fr/header.mobile.tpl'}
<script type="text/javascript" src="{$smarty.const.URL_WEB}/webapp/js/region.js" charset="UTF-8" p="{$consignee.province_id}" c1="{$consignee.city_id}" c2="{$consignee.county_id}"  id="js_region"></script>
<FORM METHOD=POST ACTION="/consignee/consignee/update" id="consignee_update" onsubmit="return false;">
<div class="menu">
   <a class="back" href="{if $form_url}{$form_url}{else}//consignee/consignee/list{/if}"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">修改收货地址</div>
</div>
<script type="text/javascript" src="{$smarty.const.URL_WEB}/webapp/js/region.js" charset="UTF-8" p="" c1="" c2=""  id="js_region"></script>
<div class="menber">
   <div class="cdz_box">
      <ul>
         <li><span>收货人</span><div class="cdz_info"><input type="text" value="{$consignee.consignee}" name="consignee" id="consignee" class="cdz_txt"></div></li>
         <li><span>手机号</span><div class="cdz_info"><input type="text" value="{$consignee.mobile}" class="cdz_txt" id="mobile" name="mobile"></div></li>
		 <li id="select_region"></li>
         <li><span>详细地址</span><div class="cdz_info"><textarea id="address" name="address">{$consignee.address}</textarea></div></li>
         <li><span>邮　编</span><div class="cdz_info"><input type="text" value="{$consignee.zip}" class="cdz_txt" id="zip" name="zip"></div></li>
      </ul>
      <div class="cdz_mr"><input type="checkbox" name="default" value="1" {if $consignee.default==1}checked{/if}>设为默认地址</div>
	  <INPUT TYPE="hidden" NAME="form_url" value="{$form_url}">
	  <INPUT TYPE="hidden" NAME="id" value="{$consignee.id}">
      <div class="cdz_btn"><input type="button" value="保存" class="btn_bc" onclick="send('consignee_update','/consignee/consignee/update');"></div>
   </div>
</div>
</FORM>
{include file='public/fr/footer.mobile.tpl'}