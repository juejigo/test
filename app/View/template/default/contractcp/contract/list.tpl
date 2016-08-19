{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}

		<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">合同管理 <small>添加以及管理合同信息</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="#">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="#">商品</a><i class="fa fa-angle-right"></i></li>
					<li><a href="#">合同列表</a></li>
				</ul>
			</div>
			<!--表格列表-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet-body">
						<!--工具-->
						<div class="table-toolbar">
							<div class="row">
								<div class="col-md-6">
									<a class="btn green" href="/contractcp/contract/add">新增 <i class="fa fa-plus"></i></a>
								</div>
							</div>
						</div>
						<!--表格-->
						<div class="dataTables_wrapper no-footer">
							<div class="table-scrollable" style="overflow-x:inherit;overflow-y:inherit;">
								<table class="table table-striped table-bordered table-hover">
									<thead>
										<tr role="row">
											<th width="80">ID</th>
											<th width="400">合同名称</th>
											<th>操作</th>
										</tr>
									</thead>
									<tbody>
									{foreach  $contractList as $i=>$row}
										<tr data-id="{$row.id}"> 
											<td>{$row.id}</td>
											<td>{$row.contract_name}</td>
											<td>
												<a href="/contractcp/contract/edit?id={$row.id}" class="btn green">编辑 <i class="glyphicon glyphicon-edit"></i></a>
												<a href="javascript:;" class="delete btn red">删除 <i class="glyphicon glyphicon-remove"></i></a>	
											</td>
										</tr>
										{/foreach}
									</tbody>
								</table>
							</div>
						</div>
						<!--分页-->
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="dataTables_paginate paging_bootstrap_full_number pull-right" id="sample_1_paginate">
									{$pagebar}
								</div>
							</div>
						</div>
						<!--分页 /-->
					</div>
				</div>
			</div>
			<!--表格结束 /-->
		</div>
	</div>
	<!--content-wrapper /-->
	
	{include file='public/admincp/footer.tpl'}
	
	<script>
//删除
$('.delete').click(function(){
	var tr = $(this).closest('tr');
	var id = $(this).closest('tr').data('id');
	if (confirm('确定删除？')){
		$.post('/contractcp/contract/delete?id='+id,function(data){
			if (data.errno == 1){
				tr.remove();
				return;
			}else{
				alert(data.errmsg);
			}
		});
	}
});
</script>
	