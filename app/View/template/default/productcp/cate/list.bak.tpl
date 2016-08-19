{include file='public/header_admincp_iframe.tpl'}

<div id="main-hd">
		<h1>商品分类</h1>
		{include file='public/tab_nav.tpl'}
</div>

<div id="main-bd">
		
		<!--<div class="search">
		<form action="/productcp/product/list" method="get">
				<input type="submit" class="btn green" value="搜索">
		</form>
		</div>-->
		
		<table class="table-list">
				<tr>
						<th>分类名</th>
						<th>操作</th>
				</tr>
				{foreach $list as $cate}
				<tr cid="{$cate.id}">
						<td>{$cate.value}</td>
						<td>
								<a href="/productcp/cate/update?id={$cate.id}">编辑</a>
								<a class="delete" href="javascript:;">删除</a>
						</td>
				</tr>
				{/foreach}
		</table>
		
		<div>{$pagebar}</div>
</div>


{include file='public/footer_admincp_iframe.tpl'}