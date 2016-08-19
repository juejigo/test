{include file='public/fr/header.mobile.tpl'}
<div class="menu">
   <a class="back" href="/user/member"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">账户安全</div>
</div>
<div class="menber">
    <div class="men_list2">
       <ul>
          <li><a href="/user/profile/changepw">修改登录密码<span></span></a></li>
		  {if $user->account==''}
		  <li><a href="/user/account/bindingmb">绑定手机<span>{$user->email}</span></a></li>
		  {/if}
		  <!--
          <li><a href="#">绑定邮箱<span>{$user->email}</span></a></li>
		  -->
       </ul>
    </div>
</div>
{include file='public/fr/footer.mobile.tpl'}