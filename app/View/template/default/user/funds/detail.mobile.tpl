{include file='public/fr/header.mobile.tpl'}
<div class="menu">
   <a class="back" href="javascript:history.back(-1);"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">明细</div>
</div>
<div class="menber">
    <div class="mingxi">
       <ul>
		  {foreach $fundsList as $i => $funds}
          <li><p class="zt"><em>{$funds.desc}</em>{date('Y-m-d H:i:s',$funds.dateline)}</p><p {if $funds.money>0}class="jea"{else}class="jeb"{/if}>{$funds.money}</p></li>
		  {/foreach}
       </ul>
    </div>
</div>
{include file='public/fr/footer.mobile.tpl'}