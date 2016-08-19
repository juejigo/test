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
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}index/index/index.mobile.css">
  <style>
  .about_block{ padding:15px;}
  .about_block img{ width:100%;margin-bottom: 10;}
  </style>
</head>
<body>
		<!--头部-->
		<header class="mui-bar mui-bar-nav">
			<a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
			<h1 class="mui-title">{$new.title}</h1>
		</header>
		<!--头部 /-->
		<div class="mui-content">
      <div class="about_block">
{$new.content}
      </div>
   </div>}
{include file="public/fr/footer.mobile.tpl"}
<script src='{$smarty.const.URL_JS}index/index/jquery.min.js'></script>
<script src='{$smarty.const.URL_JS}index/index/mui.min.js'></script>
