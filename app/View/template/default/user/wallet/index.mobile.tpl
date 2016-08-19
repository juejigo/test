{include file='public/fr/header.mobile.tpl'}
<div class="menu">
   <a class="back" href="/user/member"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">我的钱包</div>
   <a class="btn_mx" href="/user/funds/detail"><img src="{$smarty.const.URL_WEB}webapp/images/btn_mx.png"></a>
</div>
<div class="menber">
    <div class="wuliu">
       <p class="sg">余额</p>
       <em>{$balance}</em>
    </div>
    <div class="my_qb">
       <ul>
          <!--<li><a href="/user/wallet/transfer">转账</a></li>-->
          <li><a href="/funds/funds/post">提现</a></li>
          <li><a href="/user/wallet/mycoupon">优惠券</a></li>
       </ul>
    </div>
</div>
{include file='public/fr/footer.mobile.tpl'}