 {include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
 </script>
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">	
			<h3 class="page-title">{$tagList.0.tag_name}</h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/product/list">商品</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/tag/list">标签列表</a></li>
				</ul>
			</div>
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box grey-cascade">
						<div class="portlet-title">
							<div class="caption">
								记录列表（{$count}）
							</div>
							
							<div class="tools">
								<a href="javascript:;" class="collapse">
								</a>
								<a href="#portlet-config" data-toggle="modal" class="config">
								</a>
								<a href="javascript:;" class="reload">
								</a>
								<a href="javascript:;" class="remove">
								</a>
							</div>
						</div>
						<div class="portlet-body">
						<!--工具-->
							<div class="table-toolbar">
								<div class="row">
									<div class="col-md-6">
										<a class="btn red" id="all_delete" href="javascript:;">批量删除</a>
										<a class="btn red" id="delete_down" href="javascript:;">清空下架</a>
									</div>
								</div>
							</div>
							<table class="table table-striped table-bordered table-hover" id="sample_1">
							<thead>
							<tr>
								<th><input type="checkbox" class="group-checkable" id="allCheck">
									 排序
								</th>
								<th>
									 商品ID
								</th>
								<th>
									 商品名
								</th>
								<th>
									商品状态
								</th>
								<th>
									 操作
								</th>										
							</tr>
							</thead>
							<tbody id="prodList">
							{foreach $tagList as $tag}
							<tr dataid="{$tag.product_id}" data-tagid="{$tag.tag_id}" data-sortid="{$tag.order}" class="gradeX">
								<td><input type="checkbox" class="group-checkable checkbox">
								{$tag.order}										
								</td>
								<td>
								{$tag.product_id}
								</td>
								<td>
								{$tag.product_name}
								</td>
								<td>
								{if $tag.status == 0}
			                    <span class="label label-warning">待上架</span>
		                        {else if $tag.status == 2}
		                        <span class="label label-success">上架中</span>
		                        {else if $tag.status == 3}
		                        <span class="label label-danger">已下架</span>
		                        {/if}
								</td>
								<td>
									<div class="btn-group">
										<button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
											排序 <i class="fa fa-navicon"></i> <i class="fa fa-angle-down"></i>
										</button>
										<ul class="dropdown-menu" role="menu">
											<li><a href="javascript:;" class="to_top">置顶</a></li>
											<li><a href="javascript:;" class="set_sort">排序</a></li>
										</ul>
									</div>
									<a href="javascript:;" class="delete btn red">删除 <i class="glyphicon glyphicon-remove"></i></a>
									<a href="/productcp/tag/tagedit?productId={$tag.product_id}&&tagId={$tag.tag_id}" class="btn green">编辑 <i class="glyphicon glyphicon-edit"></i></a>	
								</td>
							</tr>
							{/foreach}
							</tbody>
							<tr>
								<td colspan="6">{$pagebar}</td>
							</tr>
							</table>
						</div>
					</div>
					<!-- END EXAMPLE TABLE PORTLET-->
					<!--排序填写框-->
					<div class="modal fade bs-modal-sm" id="prodSortBox" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-sm">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title">排序</h4>
								</div>
								<div class="modal-body">
									<form>
										<div class="form-group">											
											<input type="hidden" name="id" placeholder="商品ID" value="">
											<input type="hidden" name="tag" placeholder="tagId" value="">
										 	<input class="form-control" name="order" type="text" placeholder="序号" vlaue="">
										 </div>
									 </form>
								</div>
								<div class="modal-footer">
									<button type="button" data-dismiss="modal" class="btn btn-default">关闭</button>
									<button type="button" class="btn btn-primary">确定</button>
								</div>
							</div>
						</div>
					</div>
					<!--排序填写框-->
				</div>
			</div>
	</div>
</div>
<!-- END CONTENT -->

{include file='public/admincp/footer.tpl'}
<script>

</script>