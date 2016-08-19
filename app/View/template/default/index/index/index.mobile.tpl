{include file="public/fr/header.mobile.tpl"}
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}index/index/index.mobile.css">
</head>
<body class="">
	<!--头部-->
  <header class="mui-bar mui-bar-nav index">
		<div class="header_logo"><img src="{$smarty.const.URL_IMG}/wx/index_logo.png"></div>
		<div class="header_change_city"><a href="#">温州</a></div>
		<a class="open_rightb" id="openRightb"></a>
		<div class="header_seach">
			<a href="/product/product/search"><div class="header_seach_box"><i class="inedx_search"></i> 搜索关键字</div></a>
		</div>
  </header>
	<!--头部 /-->
	<div class="mui-content">
		<!--banner图-->
		<div class="banner">
			<div id="slider" class="swipe" style="visibility:visible;">
				<div class="swipe-wrap">
				    {foreach $positions as $row}
					<figure><div><a href="{$row.url}"><img src="{$row.image}" width="100%" /></a></div></figure>
					{/foreach}
				</div>
			</div>
			<nav>
			<ul id="position">
		{foreach $positions as $row}
         <li class=""></li>
         	{/foreach}
			</ul>
		</nav>

			<script src="{$smarty.const.URL_JS}index/index/swipe.js"></script>
			<script>
				var bullets = document.getElementById('position').getElementsByTagName('li');
				bullets[0].className='on';
				var slider = new Swipe(document.getElementById('slider'), {
					auto: 3000,
					continuous: true,
          callback: function (pos) {
              var i = bullets.length;
              while (i--) {
                  bullets[i].className = ' ';
              }
              bullets[pos].className = 'on';
          }
				});

			</script>
		</div>
		<!--banner图 /-->
		<!--分类-->
		<div class="index_type">
		{foreach $postition3 as $row}
			<a href="/product/product/list?tourism_type={$row.properties.params.tourism_type}"><img src="{$row.properties.image}"><p>{$row.properties.title}</p></a>
		{/foreach}
		</div>
		<!--分类 /-->
		<div class="line"></div>
		<!--友趣游精选-->
		<div class="view">
			<div class="view_header bd_b bl">友趣游精选</div>
			<div class="view_content">
					<ul class="relevant_prod_list">
					        {foreach $jxuan_product as $i=>$row}
        		{if $i < 4}
						<li><a href="/product/product/detail?id={$row.productInfo.product_id}">
						<div class="img"><img class="lazy" src="{$row.productInfo.image}"></div>
						<div class="name">{$row.productInfo.product_name}</div>
						<div class="price">￥<strong>{$row.productInfo.price}</strong></div></a></li>
				{/if}
          {/foreach}	
					</ul>
			</div>
		</div>
		<!--友趣游精选 /-->
		<div class="line"></div>
		<!--tab-->
		<div class="mui-segmented-control">
		      <a href="#chujingyou" class="mui-control-item mui-active">出境游</a>
		            <a href="#guoneiyou" class="mui-control-item">国内游</a>
      <a href="#youlun" class="mui-control-item ">自由行</a>

    </div>
		<div class="tabs">
		
		      <div id="chujingyou" class="mui-control-content mui-active">
        <div class="index_prod_content">
					<ul class="list-container">
            {if $cjy_product}
    {foreach $cjy_product as $row}
						<li class="index_prod_ali"><a href="/product/product/detail?id={$row.id}">
								<div 	class="prod_img">
									<div class="prod_count">
										距结束：<span class="count_time" data-time="{$row.down_time-time()}">获取中...</span>
									</div>
									<img class="lazy" src="{$row.image}">
									<p>出发日期：{date("Y-m-d",$row.travel_date)}</p>
								</div>
								<div class="prod_info">
									<h3>{$row.product_name}</h3>
									<div class="price">
										￥<strong class="m_r5">{$row.price}</strong>
										<!-- 起
										<del class="m_r5">市场价：￥{$row.cost_price}</del> -->
									</div>
								</div></a></li>
        {/foreach}
        {/if}
					</ul>
        </div>
      </div>
		
		      <div id="guoneiyou" class="mui-control-content">
        <div class="index_prod_content">
					<ul class="list-container">
					      {if $gny_product}
    {foreach $gny_product as $row}
						<li class="index_prod_ali"><a href="/product/product/detail?id={$row.id}">
						<div class="prod_img">
									<div class="prod_count">
										距结束：<span class="count_time" data-time="{$row.down_time-time()}">获取中...</span>
									</div>
									<img class="lazy" src="{$row.image}">
									<p>出发日期：{date("Y-m-d",$row.travel_date)}</p>
								</div>
								<div class="prod_info">
									<h3>{$row.product_name}</h3>
									<div class="price">
										￥<strong class="m_r5">{$row.price}</strong>
										<!--  起<del class="m_r5">市场价：￥{$row.cost_price}</del>-->
									</div>
								</div></a></li>
        {/foreach}
        {/if}
					</ul>
        </div>
      </div>
		
      <div id="youlun" class="mui-control-content ">
        <div class="index_prod_content">
					<ul class="list-container">
      {if $zyx_product}
      {foreach $zyx_product as $row}
						<li class="index_prod_ali"><a href="/product/product/detail?id={$row.id}">
							<div class="prod_img">
								<div class="prod_count">距结束：<span class="count_time" data-time="{$row.down_time-time()}">获取中...</span></div>
								<img class="lazy" src="{$row.image}"><p>出发日期：{date("Y-m-d",$row.travel_date)}</p>
							</div>
							<div class="prod_info">
								<h3>{$row.product_name}</h3>
								<div class="price">￥<strong class="m_r5">{$row.price}</strong>
							<!--起 <del class="m_r5">市场价：￥{$row.cost_price}</del> -->
								</div>
							</div>
						</a></li>
        {/foreach}
			{/if}

					</ul>
        </div>
      </div>


    </div>
		<!--tab /-->
		<!--底部-->
		<div class="index_footer">
			<div class="index_footer_a bd_b">
			{if $user->account!= ''}
				<a href="#">个人中心</a>
				<a href="/user/account/logout">注销</a>
				{else}
				<a href="/user/account/login">登录</a>
				<a href="/user/account/register">注册</a>
				{/if}
				<a href="#">客户端</a>
				<a href="#">客服</a>
			</div>
			<p>{$site.copyright}</p>
		</div>
		<!--底部 /-->
	</div>


  <!--侧栏-->
  <div class="side_right" id='panelRight'>
		<dl>
			<dt>

			        <div class="user_img"><img src="{if $user->iavatar!= ''} {$user->avatar} {else}  {$smarty.const.URL_IMG}/wx/user_img.png {/if}"></div>
			        {if $user->isLogin()}
								{if $user->member_name != ''}
								<a href="#">  {$user->member_name} </a> 
								 {else if  $user->account != ""}  
								 <a href="#"> {$user->account} </a>
								 {/if}
						 {else}
						<a href="/user/account/login">请登录</a>
							{/if}
			</dt>
			<dd><img src="{$smarty.const.URL_IMG}/wx/us.png"><a href="/static/html/aboutus?id=1925">关于我们</a></dd>
			<dd><img src="{$smarty.const.URL_IMG}/wx/sysm.png"><a href="/static/html/aboutus?id=1926">使用说明</a></dd>
			<dd><img src="{$smarty.const.URL_IMG}/wx/call.png"><a href="/static/html/aboutus?id=1927">联系我们</a></dd>
			<dd><img src="{$smarty.const.URL_IMG}/wx/flgg.png"><a href="/static/html/aboutus?id=1928">法律公告</a></dd>
			<dd><img src="{$smarty.const.URL_IMG}/wx/mzsm.png"><a href="/static/html/aboutus?id=1929">免责声明</a></dd>
			   {if $user->isLogin()}
			   <dd><img src="{$smarty.const.URL_IMG}/wx/logout.png"><a href="/user/account/logout">退出登录</a></dd>
			   {/if}
		</dl>
  </div>
	<div class="mui-off-canvas-backdrop" id="zz"></div>
	<!--侧栏 /-->
{include file="public/fr/footer.mobile.tpl"}
<script src='{$smarty.const.URL_JS}index/index/jquery.min.js'></script>
<script src='{$smarty.const.URL_JS}index/index/mui.min.js'></script>
<script src='{$smarty.const.URL_JS}index/index/yqy.mobile.js'></script>
<script src='{$smarty.const.URL_JS}index/index/index.mobile.js'></script>
