{include file='public/fr/header.mobile.tpl'}
<div class="menu">
   <a class="back" href="/user/wallet"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">我的红包</div>
   <a href="#" class="nav_bz">使用规则</a>
</div>
<div class="content">
	{foreach $coupons as $i => $coupon}
    <div class="hb_box {if $coupon.deadline<time() || $coupon.status!=1} hb_no{/if}">
        <div class="hb_je"><em>￥</em>{$coupon.value}</div>
        <div class="hb_xx">
           <p>全店通用</p>
           <p>有效时间：至{$coupon.end_time}</p>
           <p>使用条件：{$coupon.memo}</p>
        </div>
    </div>
	{/foreach}
</div>
{include file='public/fr/footer.mobile.tpl'}