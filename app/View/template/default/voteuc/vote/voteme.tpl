{foreach $recordlist as $record}
<li><div class="det">
<div class="head_img"><img src={$record.img} width="100%"/></div>
<div class="info">{$record.name} <p>给wo贡献了{$record.num}票</p></div>
</div>
<div class="voteTime">
{foreach $record.dateline as $date}
<p><span class="fl">贡献一票</span><span class="fr">{$date}</span></p>
{/foreach}
</div>
</li>
{/foreach}