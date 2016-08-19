{include file='public/header_cp_empty.tpl'}

<div id="main-hd">
		<h1>申请提现</h1>
</div>

<div id="main-bd">

		<div id="account">
				<img class="f1" src="{$user->avatar}" />
				<p>小鸭 <span>账户余额：{$member.balance}</span></p>
		</div>
		
		<form method="post" action="/fundscp/funds/confirm?id={$funds.id}">
		<table class="table-form">
				<tr>
						<th><label for="bankcard">提现人</label></td>
						<td>小鸭</td>
				</tr>
				<tr>
						<th><label for="bankcard">银行卡</label></td>
						<td><span class="icon-bank"></span> 建行 尾号：88888 888888 8888</td>
				</tr>
				<tr>
						<th><label for="money">提现金额</label></td>
						<td>{$funds.money}</td>
				</tr>
				<tr>
						<th></td>
						<td>
								<input class="button green" type="submit" value="确认提现" />
						</td>
				</tr>
		</table>
		</form>

</div>



{include file='public/footer_cp_empty.tpl'}