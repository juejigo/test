{foreach $playerlist as $player}
<li><a href="/vote/player/info?id={$player.id}&vote_id={$vote_id}"><div class="img"><i>{$player.player_num}号</i><img src="{$player.image}" width="100%" /></div></a>
<div class="info"><span class="name">{$player.name}</span><span class="votes"><b id="num_{$player.id}">{$player.vote_num}</b>票</span><div class="manifesto"><span>参赛宣言</span>{$player.declaration}</div>
<div class="vote_btn_box"><span class="vote_btn" onclick="votesTo({$player.id})">投票</span></div></div></li>
{/foreach}