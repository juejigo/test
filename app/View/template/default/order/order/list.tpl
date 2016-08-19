
     {include file='public/fr/headeruser.tpl'}
  <!--订单列表-->
  <div class="main">
    <div class="order_list_box">
      <div class="order_list_nav">
        <ul>
          <li {if $status ==-1}class="active"{/if}><a href="/order/order/list?status=-1">全部订单</a></li>
          <li {if $status == 0}class="active"{/if}><a href="/order/order/list?status=0">待付款({$payments})</a></li>
          <li {if $status == 1}class="active"{/if}><a href="/order/order/list?status=1">待确认({$onfirm})</a></li>
          <li {if $status == 2}class="active"{/if}><a href="/order/order/list?status=2">待出行</a></li>
        </ul>
      </div>
      <div class="order_list_title">
        <span style="width:520px;">订单信息</span>
        <span style="width:120px;">产品类型</span>
        <span style="width:120px;">规格数量</span>
        <span style="width:120px;">订单金额</span>
        <span style="width:120px;">订单状态</span>
        <span style="width:156px;">操作</span>
      </div>
      <div class="order_list">
        <ul>
        {foreach $orderList as $row}
          <li>
            <div class="order_list_header">
                <span class="fl pd">订单编号：{$row.id}</span>
                <span class="fl pd">下单时间：{date("Y-m-d",$row.dateline)}</span>
                <span class="fr">{date("Y-m-d",$row.travel_date)}出发</span>
            </div>
            <div class="order_list_contect">
                <div class="order_name fl">
                  <img src="{$row.product_image}">
                  <div class="name"><a href="/product/product/detail?id={$row.product_id}">{$row.product_name}</a></div>
                </div>
                <div class="order_list_info fl">{$row.tourism_type}</div>
                <div class="order_list_info fl">
                  <p>{$row.product_chil}</p>
                  <p>{$row.product_yon}</p>
                </div>
                <div class="order_list_info fl">￥{$row.pay_amount}</div>
                <div class="order_list_info fl">
                {if $row.status == 0}
                  <p>待付款</p>
               {else if $row.status == 1}
                   <p>待确认</p>
               {else if $row.status == 2}
                    <p>待出行</p>
               {else if $row.status == 3}
                    <p>已完成</p>
               {else if $row.status == 13}
                    <p>已退款</p>
                   {/if}
                  <p><a href="/order/order/detail?id={$row.id}">查看详情</a></p>
                </div>
                {if $row.status == 0}
                {if $row.from == 0}
                <div class="order_op fl">
                  <a href="/order/order/pay?id={$row.id}" class="btn btn_ycolor btn_raidus">立即付款</a>
                </div>
                {else}
                  <div class="order_op fl">
                 		 <a href="#" class="btn btn_ycolor btn_raidus">等待后台确认</a>
                </div>
                {/if}
                {/if}
            </div>
          </li>
          {/foreach}
        </ul>
        <!--分页-->
    <div class="page">
      {if $page == 1}
      <span class="disabled_page">首页</span>
      <span class="disabled_page">上一页</span>
      {else}
   		<a href="/order/order/list?page=1{$query}">首页 </a>
   		<a href="/order/order/list?page={$prev_page}{$query}">上一页</a>
      {/if}
      
      <!-- 前半部分 -->
      {if $page >= 1 }
        {for $j=($page-3);$j<($page);$j++}
        {if $j > 0}
         <a href="/order/order/list?page={$j}{$query}" >{$j} </a>
         {/if}
   		{/for}
      {/if}
      
      <!-- 选中部分 -->
      <a href="/order/order/list?page={$page}{$query}"  class="active">{$page} </a>

<!-- 后半部分 -->
    {for $i=($page+1);$i<= ($page+3);$i++}
      		{if $i <= $pages}
	         <a href="/order/order/list?page={$i}{$query}" >{$i} </a>
       		 {/if}
   		{/for}
      		
		{if $page == $pages}
       <span class="disabled_page">下一页</span>
      <span class="disabled_page">尾页</span>
      {else}
      <a href="/order/order/list?page={$next_page}{$query}">下一页</a>
      <a href="/order/order/list?page={$pages}{$query}">尾页</a>
      {/if}
    </div>
    <!--分页 /-->
      </div>
    </div>
  </div>
  <!--订单列表 /-->
   {include file='public/fr/footer.tpl'}
</body>
</html>
<script src="{$smarty.const.URL_JS}index/index/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_JS}index/index/yqy.min.js"></script>
