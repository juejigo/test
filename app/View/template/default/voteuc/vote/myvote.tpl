{foreach $votedlist as $voted}
<li><div class="det">
<div class="head_img"><img src={$voted.img} width="100%"/></div>
<div class="info">[{$voted.player_num}号] {$voted.name} <span>{$voted.vote_num}</span><p>给ta贡献了{$voted.num}票</p></div>
</div>
<div class="voteTime">
{foreach $voted.dateline as $date}
	<p><span class="fl">贡献一票</span><span class="fr">{$date}</span></p>
{/foreach}
</div>
</li>
{/foreach}