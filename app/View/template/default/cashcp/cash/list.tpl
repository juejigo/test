{include file='public/header_cp_empty.tpl'}

<div id="main-hd">
		<h1>资金明细</h1>
</div>

<div id="main-bd">
		
		<h4>提现记录</h4>
		<table class="table-list">
				<tr>
						<th>用户</th>
						<th>创建时间</th>
						<th>名称|交易号</th>
						<th>金额</th>
						<th>状态</th>
						<th>操作</th>
				</tr>
				{foreach $fundsList as $funds}
				<tr>
						<td>{$funds.account}</td>
						<td>{date('Y.m.d',$funds.dateline)}<br />{date('H:i',$funds.dateline)}</td>
						<td>{$funds.desc}<br /></td>
						<td>{if $funds.type == 1}<font color="red">-{$funds.money}</span>{else}<font color="green">{$funds.money}</font>{/if}</td>
						<td>{if $funds.status == -1}已取消{elseif $funds.status == 1}交易成功{elseif $funds.status == 0}待确认{/if}</td>
						<td>
								<a href="/fundscp/funds/detail?id={$funds.id}">查看</a>
						</td>
				</tr>
				{/foreach}
		</table>
		
		<div>{$pagebar}</div>
</div>


{include file='public/footer_cp_empty.tpl'}