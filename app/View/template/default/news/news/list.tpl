{include file='public/fr/header.mobile.tpl'} 
 {if !$newsList}
 	 <script>art.dialog({ 
    lock: true,
    background: '#600', // 背景色
    opacity: 0.87,	// 透明度
    content: '信息不存在或审核未通过',
    icon: 'error',
    ok: function () { 
        //art.dialog({ content: '再来一个锁屏', lock: true});
        window.location.href='/';
        return false;
    },
    cancel: false
});</script> 						 
 {/if}  
<div class="menu">
   <div class="btn_left"><a class="back2" href="javascript:history.go(-1);"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a></div>
    <div class="tit">{$newsList[0]['cate_name']}  </div>
</div>
<div class="help">
   <ul>
  {foreach $newsList as $news} 
       <li><a href="/news/news/detail/?id={$news['id']}">{$news['title']}</a></li>
  {/foreach} 
   </ul>
</div>
 


{include file='public/fr/footer.mobile.tpl'}

 