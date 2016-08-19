<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">
	<meta name="format-detection" content="telephone=no" />
	<title>友趣游</title>
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}index/index/mui.min.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}index/index/yqy.mobile.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}product/product/product.mobile.css">
</head>
<body>
	<!--头部-->
	<header class="mui-bar mui-bar-nav prod_list">
		<a class="go_home external" href="/index/index"></a>
		<h1 class="mui-title">产品列表</h1>
		<a class="go_search external" href="/product/product/search"></a>
	</header>
	<!--头部 /-->
	<!--hidden-->
	<input type="hidden" id="page" value="1" placeholder="页码">
	<input type="hidden" id="classType" value="recommend" >
	<!--hidden /-->
	<!-- 产品列表 -->
	<div class="prod_class">
		<span class="active" data-class="recommend">推荐</span>
		<!-- <span data-class="zonghe">综合</span> -->
		<span data-class="sort">销量</span>
		<span class="price" data-class="price">价格</span>
	</div>
	<div class="mui-content" style="padding-top:85px;">
		<div class="prod_list" id="prodList">
			<ul>
			</ul>
		</div>
		<div class="prod_list_load"><span class="mui-spinner"></span></div>
		<div class="prod_list_over">已经到最底了</div>
	</div>
		<!-- 产品列表 /-->
	</div>
{include file="public/fr/footer.mobile.tpl"}
<script src='{$smarty.const.URL_JS}index/index/jquery.min.js'></script>
<script src='{$smarty.const.URL_JS}index/index/mui.min.js'></script>
<script src='{$smarty.const.URL_JS}index/index/yqy.mobile.js'></script>
<script src='{$smarty.const.URL_JS}product/product/product.mobile.js'></script>
