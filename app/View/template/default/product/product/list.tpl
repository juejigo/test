 {include file='public/fr/header.tpl'}
  <!--搜索-->
  <div class="normal_bg" style="background-image:url({$smarty.const.URL_MIX}company/images/prodlistbanner.jpg);"></div>
  <div class="search" style="top:168px;">
    <form action="/product/product/list" method="get">
      <div class="go_where"><input type="text" name="keyWord" placeholder="您想去哪儿？" value="{$keyWord}" ></div>
      <div class="go_time"><input type="text" name="go_time" placeholder="出发时间"></div>
      <button type="submit">准备，出发</button>
    </form>
  </div>
  <!--搜索  /-->
  <!--中间内容-->
  <div class="main">
    <!--条件选择-->
    <div class="condition">
      <table>
        <tbody>
          <!-- <tr>
            <td class="condition_title">线路玩法</td>
            <td class="condition_nav">
              <div class="list_a">
                <a class="active" href="productList.html">不限</a>
                <a href="productList.html">韩国</a>
                <a href="productList.html">日本</a>
                <a href="productList.html">土耳其</a>
                <a href="productList.html">香港</a>
                <a href="productList.html">美国</a>
                <a href="productList.html">韩国</a>
                <a href="productList.html">日本</a>
                <a href="productList.html">韩国</a>
                <a href="productList.html">日本</a>
                <a href="productList.html">土耳其</a>
                <a href="productList.html">香港</a>
                <a href="productList.html">美国</a>
                <a href="productList.html">韩国</a>
                <a href="productList.html">日本</a>
                <a href="productList.html">韩国</a>
                <a href="productList.html">日本</a>
                <a href="productList.html">土耳其</a>
                <a href="productList.html">香港</a>
                <a href="productList.html">美国</a>
                <a href="productList.html">韩国</a>
                <a href="productList.html">日本</a>
                <a href="productList.html">韩国</a>
                <a href="productList.html">日本</a>
                <a href="productList.html">土耳其</a>
                <a href="productList.html">香港</a>
                <a href="productList.html">美国</a>
                <a href="productList.html">韩国</a>
                <a href="productList.html">日本</a>
                <a href="productList.html">韩国</a>
                <a href="productList.html">日本</a>
                <a href="productList.html">土耳其</a>
                <a href="productList.html">香港</a>
                <a href="productList.html">美国</a>
                <a href="productList.html">韩国</a>
                <a href="productList.html">日本</a>
              </div>
            </td>
            <td class="condition_op">
              <span>多选</span>
              <span class="more">更多</span>
            </td>
          </tr>
          <tr>
            <td class="condition_title">出发城市</td>
            <td class="condition_nav">
              <div class="list_a">
                <a class="active" href="">不限</a>
                <a href="productList.html">上海</a>
                <a href="productList.html">北京</a>
                <a href="productList.html">天津</a>
                <a href="productList.html">杭州</a>
                <a href="productList.html">厦门</a>
                <a href="productList.html">台湾</a>
              </div>
            </td>
            <td class="condition_op"></td>
          </tr>-->
          <tr>
            <td class="condition_title">出游时间</td>
            <td class="condition_nav">
              <div class="condition_time"><input type="text" value="{$start_time}"  class="start_time" name="start_time" placeholder="yyyy-mm-dd"></div>
              <span>至</span>
              <div class="condition_time"><input type="text"  value="{$end_time}" class="end_time" name="end_time" placeholder="yyyy-mm-dd"></div>
             
            </td>
            <td class="condition_op"></td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--条件选择 /-->
    <!--排序
      价格排序，i默认样式为p_n,降序p_d,升序p_u
    -->
    <div class="sort">
     <input  type="hidden" value="{$cate_id}"  id="cate_id">
  <!--     <a href="/product/product/list{$query}&" class="sort_a active">推荐</a>-->
    <a href="javascript:;" {if $sort == "" && $price == ""} class="sort_a active"{else}class="sort_a"{/if}>
    <input  type="hidden" value="{$zonghe}" name="zonghe">
    综合</a>
      <a href="javascript:;"   {if $sort != ""} class="sort_a active"{else}class="sort_a"{/if}>
      <input  type="hidden" value="1" name="sort">
      销量</a>
      <a href="javascript:;"  {if $price != ""} class="sort_a active"{else}class="sort_a"{/if}>
      价格<i {if $price == 1  && $price != "" }class="p_u" {else if $price ==0 && $price != ""}  class="p_d" {else} class = "p_n"{/if}></i>
      <input  type="hidden" value="{$price}" name="price"></a>
      <div class="price_form">
        <div>
          <p>价格区间</p>
          <p>
  					<input id="first_price" type="text" value="{$start_price}" name="start_price">
  					<span>-</span>
  					<input id="second_price" type="text" value="{$end_price}"  name="end_price">
  				</p>
          <a href="javascript:;" class="select_price">确定</a>
        </div>
      </div>
    </div>
    <!--排序 /-->
    <!--商品推荐-->
    <div class="prod_top">
      <div class="prod_top_photo">
        <div class="bd">
  				<ul>
  				 {foreach $jxuan_product as $row}
  					<li><a href="/product/product/detail?id={$row.productInfo.id}"><img class="slide_lazy" data-original="{$row.productInfo.image}" /></a></li>
  					{/foreach}
  					
  				</ul>
  			</div>
        <a class="prev" href="javascript:void(0)"></a>
        <a class="next" href="javascript:void(0)"></a>
        <div class="hd">
  				<ul>
  				  {foreach $jxuan_product as $data}
  					<li><img src="{$data.productInfo.image}" /></li>
  					{/foreach}
  				</ul>
  			</div>
      </div>
      <div class="prod_top_info">
      {foreach $jxuan_product as $i=>$row}
      {if $i == 0}
        <div class="prod_top_table" data-prod="{$i}" style="display:block">
        {else}
        <div class="prod_top_table" data-prod="{$i}" style="display:none">
        {/if}
          <h2>{$row.productInfo.product_name}</h2>
          <div class="price">￥{$row.productInfo.price}<span>起</span><del>市场价：￥{$row.productInfo.cost_price}</del></div>
          <p><span class="ycolor">{$row.productInfo.region_name}出发</span> | 团期：{$row.productInfo.travel_date}</p>
           <p>补差价提示：{$row.productInfo.information}</p>
          <a class="prod_top_btn" href="/product/product/detail?id={$row.productInfo.id}">立即预订</a>
          <div class="prod_end_time">距结束：<span data-time="{$row.productInfo.down_time-time()}">读取中...</span></div>
        </div>
        {/foreach}
      </div>
    </div>
    <!--商品推荐 /-->
    
    <!--商品列表-->
    {if count($product_list)>0}
    
    <div class="prod_list">
    {foreach $product_list as $row}
      <a href="/product/product/detail?id={$row.id}">
        <div class="prod_img">
          <img class="lazy" data-original="{$row.image}">
          <p class="month">出发日期：{date("Y-m-d",$row.travel_date)}</p>
          <div class="prod_end_time">距结束：<span data-time="{$row.down_time-time()}">读取中...</span></div>
        </div>
        <div class="pord_bor">
          <div class="prod_title">{$row.product_name}</div>
          <div class="prod_price">￥{$row.price}<span>起</span><del>市场价：￥{$row.cost_price}</del></div>
        </div>
      </a>
      {/foreach}
    </div>
    <!--商品列表 /-->
    <!--分页-->
    <div class="page">
      {if $page == 1}
      <span class="disabled_page">首页</span>
      <span class="disabled_page">上一页</span>
      {else}
   		<a href="/product/product/list?page=1{$query}">首页 </a>
   		<a href="/product/product/list?page={$prev_page}{$query}">上一页</a>
      {/if}
      
      <!-- 前半部分 -->
      {if $page >= 1 }
        {for $j=($page-3);$j<($page);$j++}
        {if $j > 0}
         <a href="/product/product/list?page={$j}{$query}" >{$j} </a>
         {/if}
   		{/for}
      {/if}
      
      <!-- 选中部分 -->
      <a href="/product/product/list?page={$page}{$query}"  class="active">{$page} </a>

<!-- 后半部分 -->
    {for $i=($page+1);$i<= ($page+3);$i++}
      		{if $i <= $pages}
	         <a href="/product/product/list?page={$i}{$query}" >{$i} </a>
       		 {/if}
   		{/for}
      		
		{if $page == $pages}
       <span class="disabled_page">下一页</span>
      <span class="disabled_page">尾页</span>
      {else}
      <a href="/product/product/list?page={$next_page}{$query}">下一页</a>
      <a href="/product/product/list?page={$pages}{$query}">尾页</a>
      {/if}
    
 
    </div>
    <!--分页 /-->
    {else}
    <!-- 搜索不到内容 -->
    <div class="no_prod"></div>
    <!-- 搜索不到内容 /-->
    {/if}
  </div>
  <!--中间内容 /-->
   {include file='public/fr/footer.tpl'}
<script src="{$smarty.const.URL_JS}index/index/datetimepicker.min.js"></script>
<script src="{$smarty.const.URL_JS}product/product/productList.min.js"></script>

