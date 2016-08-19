{include file="public/vote/header.tpl"}
<body class="pb_80 b_bc">
<!--排行标题-->	
<section class="title">活动排名</section>
<!--排行标题 /-->
<!--排行列表-->
<section class="ranking_list">
	<ul>
	    {foreach $voterank as $key => $rank}   
		<li>
			<a href="/vote/player/info?id={$rank.id}&vote_id={$voteinfo.id}">
				<div class="no">{$key+1}</div>
				<div class="img"><img src="{$rank.image}" width="100%" /></div>
				<span>{$rank.name}</span>
				<div class="votes_num">{$rank.vote_num}票</div>
			</a>
		</li>
		{/foreach}

	</ul>
</section>
<!--排行列表 /-->
{include file="public/vote/footer.tpl"}
</body>
</html>
<script src="{$smarty.const.URL_WEB}webapp/js/vender/jquery/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_WEB}webapp/js/cj.js"></script>
