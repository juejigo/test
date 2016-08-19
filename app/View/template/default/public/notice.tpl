<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$headerTitle}</title>
<meta content="{$headerKeywords}" name="Keywords">
<meta content="{$headerDescription}" name="Description">
<link type="image/gif" href="/favicon.gif" rel="shortcut icon">
<link type="image/gif" href="/favicon.gif" rel="bookmark">
<script src="{$smarty.const.URL_JS}lib/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_JS}public/jqueryui.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_JS}public/common.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_JS}{$module}.js"></script>
<!--<script type="text/javascript" src="https://getfirebug.com/firebug-lite-debug.js"></script>-->
<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_CSS}public/jqueryui/jqueryui.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_CSS}public/common.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_CSS}{$module}.css" />

<script type="text/javascript">
$(function()
{
	var div = $('#notice_wrapper');
	var window = document.documentElement;
	
	var divWidth = div.width();
	var divHeight = div.height();
	var windowWidth = window.clientWidth;
	var windowHeight = window.clientHeight;
	var scrollLeft = window.scrollLeft;
	var scrollTop = window.scrollTop;
	
	var left = (windowWidth - divWidth) / 2 + scrollLeft;
	var top = (windowHeight - divHeight) / 2 + scrollTop;
	
	div.css('top',top + 'px').css('left',left + 'px');
});
</script>

<style type="text/css">
<!--
div#notice_wrapper
{ width:505px;padding:25px 10px;position:absolute;border:1px #97B482 solid;background-color:#EFFFE2; }

p.icon
{ background:url("{$imageUrl}sys/notice_icon.png") no-repeat scroll 0 0 transparent; }

p#main_text
{ height:32px;text-align:center;font-size:14px;font-weight:bold;line-height:28px;color:#404040;background-position:87px 0; }

#message
{ font-size:14px;font-weight:bold; }

div#operation
{ margin-top:15px;border-bottom:1px dashed #C1C1C1; }

li.operation
{ margin-bottom:10px;color:#404040;list-style-type:disc;text-align:center; }

a:link,a:visited
{ color:#3366CC; }
-->
</style>

</head><body>

<!-- dialog -->
<div id="notice_wrapper">
		<p id="main_text" class="icon">{$title}</p>
		<div id="message">{$text}</div>
		<div id="operation">
		<ul>

		{foreach $buttons as $button}
			<li class="operation"><a href="{$button.href}">{$button.text}</a></li>
		{/foreach}

						
		</ul></div>
</div>

</body></html>