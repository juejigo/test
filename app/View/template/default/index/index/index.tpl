{include file='public/fr/header.tpl'}
  <!--广告轮播图-->
  <div class="banner">
    <!-- <div class="swiper-container">
      <div class="swiper-wrapper">
        <div class="swiper-slide"><a href="#" style="background-image:url(../images/banner1.jpg)"></a></div>
        <div class="swiper-slide"><a href="#" style="background-image:url(../images/banner2.jpg)"></a></div>
        <div class="swiper-slide"><a href="#" style="background-image:url(../images/banner3.jpg)"></a></div>
      </div>
      <div class="swiper-prev"></div>
      <div class="swiper-next"></div>
    </div> -->
    <div class="slideBox">
      <div class="bd">
        <ul>
        {foreach $positions as $row}
          <li><a href="{$row.url}" style="background-image:url({$row.image})"></a></li>
          {/foreach }
        </ul>
      </div>
      <a class="prev" href="javascript:void(0)"></a>
			<a class="next" href="javascript:void(0)"></a>
    </div>
  </div>
  <!--广告轮播图 /-->
  <!--搜索-->
  <div class="search">
    <form action="/product/product/list" method="get">
      <div class="go_where"><input type="text" name="keyWord" placeholder="您想去哪儿？" value="{$keyWord}" ></div>
      <div class="go_time"><input type="text" name="go_time" placeholder="出发时间"></div>
      <button type="submit">准备，出发</button>
    </form>
  </div>
  <!--搜索  /-->
  <!--中间内容-->
  <div class="main">
    <!--精选-->
    <div class="index_prod">
      <h2 class="title">友趣游精选</h2>
      <p class="title_f"><a href="/product/product/list?id={$row.id}">查看更多 <i class="more"></i></a></p>
      <div class="choice">
        {foreach $jxuan_product as $i=>$row}
        {if $i == 0}
        <div class="choice_left">
          <a href="/product/product/detail?id={$row.productInfo.product_id}">
            <img data-original="{$row.productInfo.image}" class="lazy">
            <div class="choice_info">
              <p class="t">{$row.productInfo.product_name}</p>
              <p class="m">￥{$row.productInfo.price}起</p>
            </div>
          </a>
        </div>
        {/if}
          {/foreach}
        <ul class="choice_right">
        {foreach $jxuan_product as $i=>$row}
        {if $i > 0}
          <li>
            <a href="/product/product/detail?id={$row.productInfo.id}">
              <img data-original="{$row.productInfo.image}" class="lazy">
              <div class="choice_info">
                <p class="t">{$row.productInfo.product_name}</p>
                <p class="m">￥{$row.productInfo.price} 起</p>
              </div>
            </a>
{/if}
          {/foreach}
        </ul>
      </div>
    </div>
    <!--精选 /-->
    
    <!--出境游-->
    <div class="index_prod">
      <h2 class="title">出境游</h2>
      <p class="title_f"><a href="/product/product/list?cate_id={$cjy_cate}">查看更多 <i class="more"></i></a></p>
      <ul class="prod_list">
            {if $cjy_product}
    {foreach $cjy_product as $row}
        <li>
          <a href="/product/product/detail?id={$row.id}">
            <div class="prod_img">
              <img data-original="{$row.image}" class="lazy">
              <p class="month">出发日期：{date("Y-m-d",$row.travel_date)}</p>
            </div>
            <div class="pord_bor">
              <div class="prod_title">{$row.product_name}</div>
              <div class="prod_price">￥{$row.price}<span>起</span><del>市场价：￥{$row.cost_price}</del></div>
              <div class="prod_end_time">距结束：<span data-time="{$row.down_time-time()}">读取中...</span></div>
            </div>
          </a>
        </li>
        {/foreach}
        {/if}
      </ul>

    </div>
    <!--出境游 /-->
    <!--国内游-->
    <div class="index_prod">
      <h2 class="title">国内游</h2>
      <p class="title_f"><a href="/product/product/list?cate_id={$gny_cate}">查看更多 <i class="more"></i></a></p>
      <ul class="prod_list">
      {if $gny_product}
    {foreach $gny_product as $row}
        <li>
          <a href="/product/product/detail?id={$row.id}">
            <div class="prod_img">
              <img data-original="{$row.image}" class="lazy">
              <p class="month">出发日期：{date("Y-m-d",$row.travel_date)}</p>
            </div>
            <div class="pord_bor">
              <div class="prod_title">{$row.product_name}</div>
              <div class="prod_price">￥{$row.price}<span>起</span><del>市场价：￥{$row.cost_price}</del></div>
              <div class="prod_end_time">距结束：<span data-time="{$row.down_time-time()}">读取中...</span></div>
            </div>
          </a>
        </li>
        {/foreach}
        {/if}
      </ul>
    </div>
    <!--国内游 /-->
        <!--邮轮-->
    <div class="index_prod">
      <h2 class="title">自由行</h2>
      <p class="title_f"><a href="/product/product/list?cate_id={$yl_cate}">查看更多 <i class="more"></i></a></p>
      <ul class="prod_list">
      {if $zyx_product}
      {foreach $zyx_product as $row}
        <li>
          <a href="/product/product/detail?id={$row.id}">
            <div class="prod_img">
              <img data-original="{$row.image}" class="lazy">
              <p class="month">出发日期：{date("Y-m-d",$row.travel_date)}</p>
            </div>
            <div class="pord_bor">
              <div class="prod_title">{$row.product_name}</div>
              <div class="prod_price">￥{$row.price}<span>起</span><del>市场价：￥{$row.cost_price}</del></div>
              <div class="prod_end_time">距结束：<span data-time="{$row.down_time-time()}">读取中...</span></div>
            </div>
          </a>
        </li>
        {/foreach}
        {/if}
      </ul>
    </div>
    <!--邮轮 /-->
  </div>
  <!--中间内容 /-->
   {include file='public/fr/footer.tpl'}
