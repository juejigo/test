{include file='public/fr/header.mobile.tpl'}
<script>
$(function(){
	setTimeout(function(){
		history.go(-1);
	},5000);
})
</script>
<div class="menu">
   <a class="back" href="javascript:history.go(-1);"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">信息提醒</div>
</div>
<div class="menber">
    <div class="nopay">
       <img src="{$smarty.const.URL_WEB}webapp/images/nopay.png">
       <h2>{$title}</h2>
       <p>5秒后页面自动跳转，您还可以：</p>
       <p>1）<a href="/">返回首页</a></p>
       <p>2）其他地方逛逛：
	   {foreach $buttons as $button}
	   <a href="{$button.href}">{$button.text}</a>
	   {/foreach}
	   <!--
	   <a href="#">登录</a>
	   <a href="#">注册</a>
	   -->
	   </p>
    </div>
</div>

{include file='public/fr/footer.mobile.tpl'}