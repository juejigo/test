{include file="public/vote/header.tpl"}
<body class="pb_80">
<!--下期活动资料-->
<section class="after_vote_box">
	<img src="{$nextVoteInfo.image}" width="100%"/>
	<div class="details">
		<h2>{$nextVoteInfo.vote_name}{if $nextVoteInfo == NULL}抱歉，下期活动还未策划完成{/if}</h2>
		<div class="tip">
			<p>报名时间：{$nextVoteInfo.start_time} 至 {$nextVoteInfo.end_time}</p>
			<p>投票时间：{$nextVoteInfo.vstart_time} 至 {$nextVoteInfo.vend_time}</p>
		</div>
		<div>
			{html content=$nextVoteInfo.rule}
		</div>
	</div>
</section>
<!--下期活动资料 /-->
{include file="public/vote/footer.tpl"}
</body>
</html>
<script src="{$smarty.const.URL_WEB}webapp/js/vender/jquery/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_WEB}webapp/js/cj.js"></script>