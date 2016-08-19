{include file='public/fr/header.mobile.tpl'}
<div class="menu">
   <a class="back" href="/order/order/list"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">查看物流</div>
</div>
<div class="menber">
    <div class="wuliu">
       <p class="sg">{$shipping.shipping_company}</p>
       <p>运单编号：<i>{$shipping.shipping_no}</i></p>
       <!--<p>物流状态：<em>签收成功</em></p>-->
    </div>
    <div class="wuliu_info">
        <div class="wli_tt">物流跟踪</div>
        <div class="wli_bb">
           <ul>
			  {foreach $shipping.data as $i => $ship}
			  <li {if $i==0}class="wl_now"{/if}><p>{$ship.content}</p>{$ship.time}</li>
			  {/foreach}
           </ul>
        </div>
    </div>
</div>
{include file='public/fr/footer.mobile.tpl'}