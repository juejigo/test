<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">
	<title>首页-投票</title>
	<link rel="stylesheet" href="{$smarty.const.URL_WEB}webapp/css/cj.min.css">
	<link rel="stylesheet" href="{$smarty.const.URL_WEB}webapp/css/style.css">
</head>
<input type="hidden" id="voteId" value="{$voteinfo.id}"/><body class="pb_80">
<!--投票banner图-->
<section class="banner">
	<div id="slider" class="swipe" style="visibility:visible;">  
	    <div class="swipe-wrap">  
	        <figure><div><a href=""><img src="{$smarty.const.URL_WEB}webapp/images/banner.jpg" width="100%"/></a></div></figure>
	        <figure><div><a href=""><img src="{$smarty.const.URL_WEB}webapp/images/banner.jpg" width="100%"/></a></div></figure>
	    </div>  
	</div>
	<script src="{$smarty.const.URL_WEB}webapp/js/vender/swipe.js"></script>
		<script>
	var slider =new Swipe(document.getElementById('slider'), {
	    auto: 3000,  
	    continuous: true
	});
	</script>
	<form action="/vote/vote/search" method="get">
		<div class="search_bg">
			<div class="search">
			    <input type="hidden" name='vote_id' value="{$voteinfo.id}"/>
				<input type="text" name="search" placeholder="请输入选手名称或者编号进行搜索" value="" />
				<input type="submit" value=""/>
			</div>
		</div>
	</form>	
</section>

<!--投票banner图 /-->	
<!--数据量-->
<section class="data_show">
	<ul>
		<li>{$voteinfo.player_num}<p>参与选手</p></li>
		<li>{$voteinfo.vote_num}<p>累积投票</p></li>
		<li>{$voteinfo.view_count}<p>访问量</p></li>
	</ul>
</section>
<!--数据量 /-->
<!--改变列表形式-->
<section class="player_type">
	<span class="t check" data-type="t"></span>
	<span class="o" data-type="o"></span>
</section>
<!--改变列表形式 /-->
<!--列表-->
<section class="player_list t">
	<div class="palyerbg"></div>
	<ul>{if $playerlist == null } <center>没有结果</center>> {/if}
	{foreach $playerlist as $player}
		<li>
			<a href="/vote/player/info?vote_id={$voteinfo.id}&id={$player.id}"><div class="img"><img src="{$player.image}" width="100%" /></div></a>
			<div class="info">
				<span class="name">{$player.name}</span>
				<span class="votes"><b>{$player.vote_num}</b>票</span>
				<div class="manifesto">
					<span>参赛宣言</span>{$player.declaration}
				</div>
				<div class="vote_btn_box">
					<span class="vote_btn" onclick="votesTo({$player.id})">投票</span>
				</div>
			</div>
		</li>
	{/foreach}
	</ul>
</section>
<!--列表 /-->
{include file="public/vote/footer.tpl"}
</body>
</html>
<script src="{$smarty.const.URL_WEB}webapp/js/vender/jquery/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_WEB}webapp/js/cj.js"></script>
<script >
var votesTo=function(playerId){
	$.ajax({
		url:"/vote/vote/ajax",
		data: {
			vote_id:VOTEID,
        	player_id:playerId
        	
        }
	}).done(function(data){
        if (data.Success) {
        	successTip("votes_to","投票成功","您还有 <span class='red'>2</span> 次投票机会",1000);
        } else {
        	alert("投票失败");
        }
    });
}</script>