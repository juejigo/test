{include file="public/vote/header.tpl"}
<body class="pb_80 b_bc">
<!--规则标题-->	
<section class="title">活动规则</section>
<!--规则标题 /-->
<!--规则列表-->
<section class="rule_box">
{html content=$voterule.rule}
</section>
<!--规则列表 /-->
{include file="public/vote/footer.tpl"}
</body>
</html>
<script src="{$smarty.const.URL_WEB}webapp/js/vender/jquery/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_WEB}webapp/js/cj.js"></script>
