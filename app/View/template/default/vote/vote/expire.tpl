{include file="public/vote/header.tpl"}
<body class="pb_80">
<!--活动基本资料-->
<section class="before_vote_box">
	<a href="/vote/vote/expirelist?vote_id={$voteinfo.id}"><div class="go_back"></div></a>
	<img src="{$expireVoteInfo.image}" width="100%"/>
	<div class="info">
		<h2>{$expireVoteInfo.vote_name}</h2>
		<p>活动时间：{$expireVoteInfo.start_time}至{$expireVoteInfo.vend_time}</p>
	</div>
	<i class="vote_over"></i>
</section>
<!--活动基本资料 /-->
<!--数据量-->
<section class="data_show">
	<ul>
		<li>{$expireVoteInfo.player_num}<p>参与选手</p></li>
		<li>{$expireVoteInfo.vote_num}<p>累积投票</p></li>
		<li>{$expireVoteInfo.view_count}<p>访问量</p></li>
	</ul>
</section>
<!--数据量 /-->
<!--获奖情况-->
<section class="bt_nr">
	<div class="title">
		<span>获奖情况</span>
	</div>
	<div class="details">
		{html content=$expireVoteInfo.awards}
	</div>
</section>
<!--获奖情况 /-->
<!--活动简介-->
<section class="bt_nr">
	<div class="title">
		<span>活动规则简介</span>
	</div>
	<div class="details">
		{html content=$expireVoteInfo.rule}
	</div>
</section>
<!--活动简介 /-->
{include file="public/vote/footer.tpl"}
</body>
</html>
<script src="{$smarty.const.URL_WEB}webapp/js/vender/jquery/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_WEB}webapp/js/cj.js"></script>