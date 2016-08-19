<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">
	<title>{$voteinfo.vote_name}　投票活动</title>
	<link rel="stylesheet" href="{$smarty.const.URL_WEB}webapp/css/cj.min.css">
	<link rel="stylesheet" href="{$smarty.const.URL_WEB}webapp/css/style.css">
</head>
<input type="hidden" id="voteId" value="{$voteinfo.id}"/>
{if $issub == 0}
<!--未关注公会显示内容-->	
<div class="follow_box">关注我们有更多惊喜哦~<a href="http://mp.weixin.qq.com/s?__biz=MzI0MTEyOTY5MQ==&mid=402572104&idx=1&sn=b67092e6b467ecac57d44281945ffea0&scene=0&previewkey=sBQYSRJtvpGj9W74NWZio8wqSljwj2bfCUaCyDofEow%3D#wechat_redirect">关注</a></div>
<!--未关注公会显示内容 /-->
{/if}