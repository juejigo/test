{include file="public/fr/header.mobile.tpl"}
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}product/product/product.mobile.css">
</head>

<body>
	<header class="mui-bar mui-bar-nav prod">
		<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
		<h1 class="mui-title">
  		距结束
			<p class="count_time" data-time="{$product.clock}">获取中...</p>
  	</h1>
  	<a class="go_home" href="/index"></a>
	</header>
	<nav class="mui-bar mui-bar-tab bd_t prod">
		<a href="javascript:;" class="prod_reserve_btn">立即预定</a>
		<div class="ewm_box">
				<img src="{$site.app_image}">
				<p>请下载APP预订</p>
		</div>
	</nav>
	<div class="mui-content">
		<!--banner图-->
		<div class="banner">
					<div id="slider" class="swipe" style="visibility:visible;">
					
						<div class="swipe-wrap">
						
						 {foreach $product_image as $row}
							<figure>
								<div>
									<a href="javascript:;"><img src="{$row.image}" width="100%" /></a>
								</div>
							</figure>
							{/foreach}
						</div>
					</div>
					<script src="{$smarty.const.URL_JS}product/product/swipe.mobile.js"></script>
					<script>
						var slider = new Swipe(document.getElementById('slider'), {
							auto: 3000,
							continuous: true
						});
					</script>
					<span>产品编号：{$product.sn}</span>
				</div>
		<!--banner图 /-->
			<div class="prod_title">
					<h3>{$product.product_name}</h3>
					<div class="price">
						￥<strong class="m_r5">{$product.price}</strong>
						<!-- 起
						<del class="m_r5">￥{$product.cost_price}</del>
						<span>{$product.discount}</span> -->
					</div>
				</div>
		<div class="line"></div>
		<!--机票信息-->
						<div class="view">
					<div class="view_header bd_b">机票信息</div>
					<div class="view_content">
					  {foreach $ticket_list as $row}
						<div class="plane_list" >
								<span class="type {if $row.type == 1} bd_t {/if}">{if $row.type == 0}去程{else if $row.type == 1}返程{/if}</span>
								<div class="info">
									<p><span>{date("Y-m-d",$row.time)}</span><span>{$row.go_area}<i class="plane_go"></i>{$row.return_area}</span></p>
									<ul>
										<li class="plane_time">
											<p class="f22">{date("H:i",$row.go_time)}</p>
											<p>{$row.go_airport}</p>
										</li>
										<li class="plane_img"></li>
										<li class="plane_time">
											<p class="f22">{date("H:i",$row.return_time)}</p>
											<p>{$row.return_airport}</p>
										</li>
									</ul>
									<p><span>{$row.company}</span><span>{$row.flight}</span><span>{$row.berths}</span></p>
								</div>
						</div>
						{/foreach}

					</div>
				</div>
		<!--机票信息 /-->
		<div class="line"></div>
		<!--行程-->
		<div class="view">
					<div class="view_header">行程</div>
					<div class="view_content">
						<div class="view_content_inner no_pt">
						            {foreach $product['trip']  as $row}
								<div class="trip_list">
									<div class="tripdays_con">
										<dl>
			                  <dt><span class="trip_con"></span></dt>
			                  <dd><div class="dayway_tit"><span class="ycolor">第{$row.sort}天</span><span class="daywayback">{$row.title}</span></div></dd>
			              </dl>
										<div class="trip_details">
							{html content=$row.content}
              			</div>
									</div>
								</div>
								{/foreach}

						</div>
					</div>
			<div class="view_footer tc"><a href="javascript:;" class="view_footer_btn" onclick="open_view('trip')">查看更多</a></div>
		</div>
		<!--行程 /-->
		<div class="line"></div>
		<!--产品特色-->
		<div class="view">
			<div class="view_header">产品特色</div>
					<div class="view_content">
						<div class="view_content_inner no_pt">
						{$product.features_info}
						</div>
					</div>
			<div class="view_footer tc"><a href="javascript:;" class="view_footer_btn" onclick="open_view('explain')">查看更多</a></div>
		</div>
		<!--产品特色 /-->
		<div class="line"></div>
		<!--须知&费用-->
		<div class="view_url" onclick="open_view('notice')">产品须知<a class="mui-icon mui-icon-forward mui-pull-right"></a></span></div>
		<!--须知&费用 /-->
		<div class="line"></div>


		<!--行程view-->
		<div class="page" id='trip'>
			<header class="mui-bar mui-bar-nav">
				<a class="mui-icon mui-icon-left-nav mui-pull-left" onclick="close_view(this)"></a>
				<h1 class='mui-title'>行程</h1>
			</header>
			<div class="mui-content">
				<div class="content_block">
					      {foreach $product['trip']  as $row}
					<div class="trip_list">
						<div class="tripdays_con">
							<dl>
									<dt><span class="trip_con"></span></dt>
									<dd><div class="dayway_tit"><span class="ycolor">第{$row.sort}天</span><span class="daywayback">{$row.title}</span></div></dd>
							</dl>
							<div class="trip_details">
							{$row.content}
							</div>
							<div class="trip_img">
							  {foreach $row.images as $data}
								<img src="{$data}">
								   {/foreach}
							</div>
						</div>
					</div>
					{/foreach}
					</div>
				</div>
			</div>
		</div>
		<!--行程view /-->
		<!--产品特色view-->
		<div class="page" id='explain'>
			<header class="mui-bar mui-bar-nav">
				<a class="mui-icon mui-icon-left-nav mui-pull-left" onclick="close_view(this)"></a>
				<h1 class='mui-title'>产品特色</h1>
			</header>
			<div class="mui-content">
				<div class="content_block">
						{html content=$product.features_content}
				</div>
			</div>
		</div>
		<!--产品特色view /-->
		<!--须知&费用view-->
		<div class="page" id='notice'>
			<header class="mui-bar mui-bar-nav">
				<a class="mui-icon mui-icon-left-nav mui-pull-left" onclick="close_view(this)"></a>
				<h1 class='mui-title'>须知&费用</h1>
			</header>
			<div class="mui-content">
				<div class="content_block">
					{html content=$product.cost_need}
				</div>
			</div>
		</div>
		<!--须知&费用view /-->
	</div>
{include file="public/fr/footer.mobile.tpl"}
<script src='{$smarty.const.URL_JS}index/index/jquery.min.js'></script>
<script src='{$smarty.const.URL_JS}index/index/mui.min.js'></script>
<script src='{$smarty.const.URL_JS}index/index/yqy.mobile.js'></script>
<script src='{$smarty.const.URL_JS}product/product/product.mobile.js'></script>
