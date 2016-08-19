{include file='public/fr/header.mobile.tpl'}

<script>
var p = 1;
var product_id = '{$product_id}';
var grade = '{$grade}';
</script>
<script type="text/javascript" src="{$smarty.const.URL_WEB}webapp/js/feedback.js"></script>
<div class="menu2">
   <a class="back2" href="javascript:history.go(-1);"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">商品评价</div>
</div>
<div class="goods_bbs">
   <div class="bbs_nav">
     <ul>
        <li {if $grade==3}class="cur"{/if}><a href="/product/feedback/list?product_id={$product_id}&grade=3">好评</a></li>
        <li {if $grade==2}class="cur"{/if}><a href="/product/feedback/list?product_id={$product_id}&grade=2">中评</a></li>
        <li {if $grade==1}class="cur"{/if}><a href="/product/feedback/list?product_id={$product_id}&grade=1">差评</a></li>
     </ul>
   </div>
   <div class="bbs_list">
	   {foreach $feedbackList as $i => $feedback}
       <div class="bl_box">
          <div class="blbox_tt"><span class="star{$feedback.grade}"></span><em>{$feedback.member_name}</em>{$feedback.dateline}</div>
          <div class="blbox_info">{$feedback.content}</div>
       </div>
	   {/foreach}
   </div>
   <div class="bbs_more"><a href="javascript:get_more();">+点击加载更多评价...</a></div>
</div>
{include file='public/fr/footer.mobile.tpl'}