{include file='public/fr/headeruser.tpl'}
  <!--收藏列表-->
  <div class="main">
    <div class="collect_list_box">
      <div class="collect_list_title">
        <span style="width:600px;">商品名称</span>
        <span style="width:140px;">售价</span>
        <span style="width:140px;">市场价</span>
        <span style="width:318px;">操作</span>
      </div>
      <div class="collect_list">
        <ul>
        {foreach $favorite_list as $row}
          <li>
            <div class="collect_list_contect">
                <div class="collect_name fl">
                  <img src="{$row.image}">
                  <div class="name">{$row.title}</div>
                </div>
                <div class="collect_list_info fl ycolor">￥{$row.price}</div>
                <div class="collect_list_info fl"><del>￥{$row.cost_price}</del></div>
                <div class="collect_op fl tr">
                  <a href="/product/product/detail?id={$row.dataid}" class="btn btn_ycolor btn_raidus collect_list_btn">预订</a><a href="javascript:;" class="btn btn_raidus collect_list_btn" onclick="collectCancel({$row.favorite_id},this)">取消收藏</a>
                </div>
            </div>
          </li>
          {/foreach}
        </ul>
               <!--分页-->
               {if $page > 0}
               
    <div class="page">
      {if $page == 1}
      <span class="disabled_page">首页</span>
      <span class="disabled_page">上一页</span>
      {else}
   		<a href="/favorite/favorite/list?page=1{$query}">首页 </a>
   		<a href="/favorite/favorite/list?page={$prev_page}{$query}">上一页</a>
      {/if}
      
      <!-- 前半部分 -->
      {if $page >= 1 }
        {for $j=($page-3);$j<($page);$j++}
        {if $j > 0}
         <a href="/favorite/favorite/list?page={$j}{$query}" >{$j} </a>
         {/if}
   		{/for}
      {/if}
      
      <!-- 选中部分 -->
      <a href="/favorite/favorite/list?page={$page}{$query}"  class="active">{$page} </a>

<!-- 后半部分 -->
    {for $i=($page+1);$i<= ($page+3);$i++}
      		{if $i <= $pages}
	         <a href="/favorite/favorite/list?page={$i}{$query}" >{$i} </a>
       		 {/if}
   		{/for}
      		
		{if $page == $pages}
       <span class="disabled_page">下一页</span>
      <span class="disabled_page">尾页</span>
      {else}
      <a href="/favorite/favorite/list?page={$next_page}{$query}">下一页</a>
      <a href="/favorite/favorite/list?page={$pages}{$query}">尾页</a>
      {/if}
    
 
    </div>
    {/if}
    <!--分页 /-->
      </div>
    </div>
  </div>
  <!--订单列表 /-->
   {include file='public/fr/footer.tpl'}
<script src="{$smarty.const.URL_JS}user/member/user.js"></script>>
