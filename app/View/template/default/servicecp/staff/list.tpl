{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}

<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">专属客服 <small>添加及管理客服</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/usercp/member/list">会员</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/servicecp/staff/list">专属客服</a></li>
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
									<a class="btn green" href="/servicecp/staff/add">新增 <i class="fa fa-plus"></i></a>
									<a class="all_delete btn red" href="javascript:;">删除勾选项</a>
								</div>
							</div>
						</div>
						<!--表格-->
						<div class="dataTables_wrapper no-footer" id="sample_1_wrapper">
							<div class="table-scrollable" style="overflow-x:inherit;overflow-y:inherit;">
								<table class="table table-striped table-bordered table-hover dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
									<thead>
										<tr role="row">
											<th width="80"><input type="checkbox" class="group-checkable" id="allCheck">ID</th>
											<th width="100">姓名</th>
											<th width="80">照片</th>
											<th width="100">微信</th>
											<th width="100">电话</th>
											<th width="450">自我介绍</th>
											<th>操作</th>
										</tr>
									</thead>
									<tbody>
									{foreach $staffList as $i => $staff}
										<tr data-id="{$staff.id}" class="gradeX odd" role="row"> 
											<td><input type="checkbox" class="group-checkable checkbox">{$staff.id}</td>	
											<td>{$staff.staff_name}</td>
											<td><img src="{$staff.avatar}" width="80" height="80px"></td>
											<td>{$staff.wx}</td>
											<td>{$staff.phone}</td>
											<td>{$staff.introduce}</td>
											<td>
												<a href="/servicecp/staff/edit?id={$staff.id}" class="btn green">编辑 <i class="glyphicon glyphicon-edit"></i></a>
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
<!-- END CONTENT -->

{include file='public/admincp/footer.tpl'}

<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
{literal}
<script>
$(function() {    
	region.init($(".select_region"));
});
</script>
{/literal}