{include file='public/fr/header.tpl'}
  <!--中间内容-->
  <div class="main">
    <!--面包屑-->
    <div class="page_bar">
      <ul class="page_breadcrumb">
				<li><a href="/index">网站</a><i class="page_right">></i></li>
				<li><a href="/product/product/list?cate_id={$cate_id}">{$cate_name}</a><i class="page_right">></i></li>
				<li><a href="/product/product/detail?id={$product_id}">{$product.product_name}</a></li>
			</ul>
    </div>
    <!--面包屑 /-->
    <!--产品轮播图-->
    <div class="prod_slide">
      <div class="bd">
        <ul>
        {foreach $product_image as $row}
       <li><a href="javascript:;"><img src="{$row.image}" width="700px"  height="440px"/></a></li>
       {/foreach}

        </ul>
      </div>
      <a class="prev" href="javascript:void(0)"><span></span></a>
			<a class="next" href="javascript:void(0)"><span></span></a>
    </div>
    <!--产品轮播图 /-->
    <!--hidden-->
    <input type="hidden" id="id" placeholder="产品id" value="{$childer_product.parent_id}">
    <input type="hidden" id="prodTime" placeholder="出行日期" value="{date("Y-m-d",$results.travel_date)}">
    <input type="hidden" id="prodAdultPrice" placeholder="成人价" value="{$results.price}">
    <input type="hidden" id="prodChildPrice" placeholder="儿童价" value="{$results.child_price}">
    <input type="hidden" id="prodId" placeholder="日期ID" value="{$childer_product.id}">
    <input type="hidden" id="prodStock" placeholder="库存" value="{$results.stock}">
    <input type="hidden" id="calendarUrl" value="ajax?op=product_items">
	<input type="hidden" id="planeUrl" value="ajax?op=product_ticket">
    <!--hidden-->
    <!--左侧-->
    <div class="prod_left">
      <!--商品标题价格-->
      <div class="prod_detail clearfix">
        <div class="title">
          <h2>{$product.product_name}</h2>
          <p>{$product.brief}</p>
        </div>
        <div class="price">
          <del>市场价：￥{$product.cost_price}</del>
          <h1>￥{$product.price}</h1>
        </div>
      </div>
      <!--商品标题价格 /-->
      <!--日历-->
      <div class="prod_calenda"></div>
      <!--日历 /-->
      
      
      <!--邮轮信息-->
      {if $product.addon != "" }
      <div class="prod_boat clearfix">
        <input type="hidden" id="roomId" value="">
        <div class="prod_boat_title">选择房型</div>
        <div class="prod_boat_list">
          <div class="title">
            <span style="width:190px;">房型</span>
            <span>甲板</span>
            <span>面积</span>
            <span>可住人数</span>
            <span>间数</span>
            <span>价格</span>
            <span>操作</span>
          </div>
          <div class="list">
            <ul>
            {foreach $product.addon as $row}
              <li class="row">
                <span class="name">{$row.addon_name}</span>
                <span>{$row.extra.floor}</span>
                <span>{$row.extra.area}</span>
                <span>{$row.extra.num}</span>
                <span>{$row.extra.stock}</span>
                <span class="ycolor">￥{$row.price}起</span>
                <span><a href="javascript:;" class="room" data-id="{$row.room_id}">选择</a></span>
              </li>
              <li class="detail">
                <div class="img"><img src="{$row.image}"></div>
                <div class="info">
                 {$row.facilities}
                </div>
              </li>
			{/foreach}
            </ul>
          </div>
        </div>
      </div>
      {/if}
      <!--邮轮信息 /-->
      
      
      <!--机票信息-->
      <div class="prod_plane">
        <div class="prod_plane_title">
          机票信息
          <span>酒店信息仅供参考，最终以出团通知为准。所有时间均为当地时间。</span>
        </div>
        <div class="prod_plane_info">
        {foreach $ticket_list as $row}
          <div class="list">
            <ul>
              <li class="plane_type">{if $row.type == 0}去程{else if $row.type == 1}返程{/if}</li>
              <li class="plane_name"><p class="f14 mt5" title="{$row.company}">{$row.company}{$row.flight}</p><p>{date("Y-m-d",$row.time)}</p></li>
              <li class="plane_time"><p class="f14 b mt10">{$row.go_area}</p><p class="f24 b mt10">{date("H:i",$row.go_time)}</p><p class="f14">{$row.go_airport}</p></li>
              <li class="plane_img"></li>
              <li class="plane_time"><p class="f14 b mt10">{$row.return_area}</p><p class="f24 b mt10">{date("H:i",$row.return_time)}</p><p class="f14">{$row.return_airport}</p></li>
              <li class="plane_total"><p class="time_c mt5">航行时间</p><p>{$row.spend_time}</p></li>
            </ul>
          </div>
          {/foreach}
       
        </div>
      </div>
      <!--机票信息 /-->
      <!--table标签-->
      <div class="prod_tab_title">
        <ul>
          <li class="select"><a href="javascript:;">行程</a></li>
          <li><a href="javascript:;">产品特色</a></li>
          <li><a href="javascript:;">须知</a></li>
        </ul>
      </div>
      <!--table标签-->
      <!--table内容-->
      <div class="prod_tab_info">
        <!--产品行程-->
        <div class="prod_tab_trip clearfix" id="table_1">
          <div class="prod_tab_info_title">产品行程</div>
          <!--行程每日按钮-->
          <div class="day_list">
            <div>
            {foreach $product['trip']  as $row}
            {if $row.sort ==1 }
              <a href="javascript:;" class="current">第1天</a>
              {else}
              <a href="javascript:;">第{$row.sort}天</a>
              {/if}
              {/foreach}
            </div>
          </div>
          <!--行程每日按钮 /-->
          <!--行程每日具体内容-->
          {foreach $product['trip']  as $row}
          <div class="trip_list" id="day{$row.sort}">
            <div class="tripdays_con">
              <dl>
                  <dt><span class="trip_con"></span></dt>
                  <dd><div class="dayway_tit"><span class="ycolor">第{$row.sort}天</span><span class="daywayback">{$row.title}</div></dd>
              </dl>
              <div class="trip_details">
				{$row.content}
              </div>
              <div class="trip_img">
              {foreach $row.images as $data}
                <img class="lazy" data-original="{$data}">
                {/foreach}
              </div>
            </div>
          </div>
          {/foreach}
          <!--行程每日具体内容 /-->
        </div>
        <!--产品行程 /-->
        <!--产品特色-->
        <div class="prod_tab_style" id="table_2">
          <div class="prod_tab_info_title">产品特色</div>
          <div class="notice">
			{html content=$product.features_content}
          </div>
        </div>
        <!--产品特色 /-->
        <!--须知-->
        <div class="prod_tab_notice" id="table_3">
          <div class="prod_tab_info_title">须知</div>
          <div class="notice">
            {html content=$product.cost_need}
          </div>
        </div>
        <!--须知 /-->
      </div>
      <!--table内容 /-->
    </div>
    <!--左侧 /-->
    <!--右侧-->
    <div class="prod_right">
      <div class="order">
        <dl>
          <dt>产品信息</dt>
          <dd><label>出发城市：</label>{$product.origin}</dd>
          <div id="orderInfo">
          <dd><label>出发日期：</label>{date("Y-m-d",$childer_product.travel_date)}</dd>
          <dd><label>成人价：</label><span class="ycolor">￥{intval($results.price)}</span>/人</dd>
          <dd><label>儿童价：</label><span class="ycolor">￥{intval($results.child_price)}</span>/儿童</dd>
          <dd>
            <label>成人数：</label>
            <div class="num_view" data-type="child">
    					<a href="javascript:;" class="min">-</a><span>1</span><a href="javascript:;" class="add">+</a>
    					<input type="hidden" id="adult" value="1">
    				</div>
          </dd>
          <dd>
            <label>儿童数：</label>
            <div class="num_view" data-type="adult">
    					<a href="javascript:;" class="min">-</a><span>0</span><a href="javascript:;" class="add">+</a>
    					<input type="hidden" id="child" value="0">
    				</div>
          </dd>
          </div>
        </dl>
        <a href="javascript:;" class="btn btn_block btn_ycolor btn_raidus" id="reserve">立即预订</a>
        <div class="down_img">
          <p>请下载APP预订</p>
          <img src="{$site.app_image}" width="228px">
        </div>
        <div class="others">
        <a href="javascript:;" class="collect {if $is_favorite == 1}has {/if}" id="collect">收藏</a>
          <a href="javascript:;" class="share" onclick="jiathis_sendto('weixin');return false;">分享</a>
        </div>
      </div>
    </div>
    <!--右侧 /-->
  </div>
  <!--中间内容 /-->
   {include file='public/fr/footer.tpl'}
<script src="{$smarty.const.URL_JS}index/index/datetimepicker.min.js"></script>
<script src="http://v3.jiathis.com/code_mini/jia.js"></script>