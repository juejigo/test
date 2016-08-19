{include file="public/vote/header.tpl"}
<body class="pb_80 b_bc">
<!--信息栏-->	
<section class="user_info">
	<div class="head_img"><img src="{$voteplayer.image}{$memberinfo.avatar}" width="100%"/></div>
	<div class="info">{if !$voteplayer == null}{$voteplayer.player_num}号 {/if}{$voteplayer.name}{$memberinfo.member_name}<p>{if !$voteplayer == null}得票数：{$voteplayer.vote_num} {/if}</p></div>
	<div class="operate">
		<i class="reload" onclick="window.location.reload();"></i>
		<i class="home" url="/vote/vote?vote_id={$voteinfo.id}"></i>
	</div>
</section>
<!--信息栏 /-->
<!--tab-->
<section class="user_tab">
	<ul>
		<li class="check"><h2>谁给我投票</h2>{if $voteplayer == null }0{/if}{$voteplayer.vote_num}票</li>
		<li><h2>我给谁投票</h2>{$votesum}票</li>
	</ul>
</section>
<div class="blank"></div>
<!--tab /-->
<!--谁给我投票列表-->
<input type="hidden" id="forMePage" value="1" />
<ul class="votes_list" style="display: block;" id="forMe"></ul>

<!--谁给我投票列表 /-->
<!--我给谁投票列表-->
<input type="hidden" id="forOtherPage" value="1" />
<ul class="votes_list" id="forOther"></ul>
<!--我给谁投票列表 /-->
{include file="public/vote/footer.tpl"}
</body>
</html>
<script src="{$smarty.const.URL_WEB}webapp/js/vender/jquery/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_WEB}webapp/js/cj.js"></script>
<script src="{$smarty.const.URL_WEB}webapp/js/user.js"></script>