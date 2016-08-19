{include file='public/fr/header.mobile.tpl'}
<div class="menu">
   <a class="back" href="/user/member"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">我的业绩</div>
   <a class="btn_tj" href="javascript:;"><img src="{$smarty.const.URL_WEB}webapp/images/btn_bza.png"></a>
</div>
<div class="menber">
    <div class="mem_sy"><a href="/user/funds/detail?type=1"><p>提成</p>{$shareIncome}<span></span></a></div>
    <div class="mem_sy"><a href="/user/funds/detail?status=0"><p>未确认</p>{$unconfirmIncome}<span></span></a></div>
    <div class="men_md"><a href="/user/funds/referee">会员名单<span>{$refereeCount}人</span></a></div>
</div>
{include file='public/fr/footer.mobile.tpl'}