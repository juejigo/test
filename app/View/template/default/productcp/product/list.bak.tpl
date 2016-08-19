{include file='public/header_admincp_iframe.tpl'}

<div id="main-hd">
		<h1>产品列表</h1>
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
						<th>图片</th>
						<th>产品名</th>
						<th>价格</th>
						<th>发布时间</th>
						<th>操作</th>
				</tr>
				{foreach $productList as $product}
				<tr dataid="{$product.id}">
						<td><img width="50" src="{thumbpath source=$product.image width=220}" /></td>
						<td>{$product.product_name}</td>
						<td>{price value=$product.price}</td>
						<td>{date('Y-m-d',$product.dateline)}</td>
						<td>
								<a href="/productcp/product/update?id={$product.id}">编辑</a>
								<a class="delete" href="javascript:;">删除</a>
						</td>
				</tr>
				{/foreach}
		</table>
		
		<div>{$pagebar}</div>
</div>


{include file='public/footer_admincp_iframe.tpl'}