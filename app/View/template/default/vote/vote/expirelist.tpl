{include file="public/vote/header.tpl"}
<body class="pb_80 b_bc">
<!--往期列表-->
<section class="before_list">
{foreach $endVoteList as $voteList}
	<a href="/vote/vote/expire?vote_id={$voteinfo.id}&expire_id={$voteList.id}">
		<img src="{$voteList.image}" width="100%"/>
		<div class="info">
			<h2>{$voteList.vote_name}</h2>
			<p>活动时间：{$voteList.start_time}至{$voteList.vend_time} </p>
		</div>
		<i class="vote_over"></i>
	</a>
{/foreach}
</section>
<!--往期列表 /-->
{include file="public/vote/footer.tpl"}
</body>
</html>
<script src="{$smarty.const.URL_WEB}webapp/js/vender/jquery/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_WEB}webapp/js/cj.js"></script>