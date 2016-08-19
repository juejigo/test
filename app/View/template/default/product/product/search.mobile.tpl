{include file="public/fr/header.mobile.tpl"}
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}index/index/index.mobile.css">
</head>
<body>
		<!--头部-->
		<header class="mui-bar mui-bar-nav">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
			<h1 class="mui-title">搜索</h1>
		</header>
		<!--头部 /-->
		<div class="mui-content">
			<form action="/product/product/list" method='get' class="search_form">
					<div class="search_input">
						<span class="mui-icon mui-icon-search"></span>
						<input type="search" class="mui-input-clear" placeholder="输入搜索关键字" name="keyWord" id="keyWord">
					</div>
					<a href="javascript:;" id="search_submit">确定</a>
			</form>
			<!--搜索记录-->
			<div class="search_box bd_b bd_t">
				<div class="line_title">历史记录</div>
				<div class="prod_key" id="prodkey">

				</div>
				<button type="button" class="mui-btn mui-btn-block"  onclick="_removeall('prod');">清空历史记录</button>
			</div>
			<!--搜索记录 /-->
		</div>
{include file="public/fr/footer.mobile.tpl"}
<script src='{$smarty.const.URL_JS}index/index/jquery.min.js'></script>
<script src='{$smarty.const.URL_JS}index/index/mui.min.js'></script>
<script src='{$smarty.const.URL_JS}index/index/yqy.mobile.js'></script>
<script src='{$smarty.const.URL_JS}index/index/index.mobile.js'></script>
