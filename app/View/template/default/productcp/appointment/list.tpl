{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">预约管理 <small>管理预约信息</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/product/list">商品管理</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/appointment/list">预约列表</a></li>
				</ul>
			</div>
			<!--搜索栏-->
			<form action="" id="search" name="search" method="get">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr role="row">
								<th class="sorting_asc" width="10%">用户名</th>
								<th class="sorting_asc" width="10%">用户ID</th>
								<th class="sorting_asc" width="10%">目的地</th>
								<th class="sorting_asc" width="20%">手机</th>
								<th class="sorting_asc" width="20%">预约日期</th>
								<th class="sorting_asc" width="10%">预约类型</th>
								<th class="sorting asc" width="10%">状态</th>
								<th class="sorting_asc">操作</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<input type="search" class="form-control" placeholder="用户名" name="member_name" aria-controls="sample_1" value="{$params.member_name}">
								</td>
								<td>
									<input type="search" class="form-control" placeholder="用户ID" name="member_id" aria-controls="sample_1"  value="{$params.member_id}">
								</td>
								<td>
									<input type="search" class="form-control" placeholder="目的地" name="destination" aria-controls="sample_1"  value="{$params.destination}">
								</td>
								<td>
									<input type="search" class="form-control" placeholder="手机" name="phone" aria-controls="sample_1" value="{$params.phone}">
								</td>
								<td>
									<div data-date-format="yyyy-mm-dd" class="input-group date date-picker margin-bottom-5">
										<input type="text" placeholder="开始时间" name="dateline_from" readonly="" class="form-control form-filter input-sm" value="{$params.dateline_from}">
										<span class="input-group-btn">
										<button type="button" class="btn btn-sm default"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
									<div data-date-format="yyyy-mm-dd" class="input-group date date-picker">
										<input type="text" placeholder="截至时间" name="dateline_to" readonly="" class="form-control form-filter input-sm" value="{$params.dateline_to}">
										<span class="input-group-btn">
										<button type="button" class="btn btn-sm default"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</td>
								<td>
									<select class="form-control" name="tourism_type">
									    <option value="">所有</option>
										<option value="1" {if $params.tourism_type == 1}selected="selected"{/if}>跟团游</option>
										<option value="2" {if $params.tourism_type == 2}selected="selected"{/if}>自助游</option>
										<option value="3" {if $params.tourism_type == 3}selected="selected"{/if}>自由行</option>
										<option value="4" {if $params.tourism_type == 4}selected="selected"{/if}>自驾游</option>
										<option value="5" {if $params.tourism_type == 5}selected="selected"{/if}>目的地服务</option>
									</select>
								</td>
								<td>
									<select class="form-control" name="status">
										<option value="">所有</option>
										<option value="1"{if $params.status == 1}selected="selected"{/if}>已确认</option>
										<option value="0"{if $params.status === '0'}selected="selected"{/if}>待确认</option>
									</select>
								</td>
								<td>
									<div class="margin-bottom-5">
										<!-- <input type="hidden" name="area" value="0"> -->
										<button class="btn btn-sm yellow filter-submit margin-bottom" type="submit"><i class="fa fa-search"></i> 搜索</button>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</form>
			<!--搜索栏 /-->
			<!--表格列表-->
			<div class="row">
				<div class="col-md-12">			
				<div class="portlet box grey-cascade">			
				<div class="portlet-title">
							<div class="caption">
								预约列表（{$count}）
							</div>
						</div>
					<div class="portlet-body">
						<!--工具-->
						<div class="table-toolbar">
							<div class="row">
								<div class="col-md-6">
									<a class="btn green" href="{str_replace('/productcp/appointment/list?page={page}&','/productcp/appointment/export?',$query)}">导出 </a>
									<a class="btn red" id="all_delete" href="javascript:;">批量删除</a>
								</div>
							</div>
						</div>

						<!--表格-->
						<div class="dataTables_wrapper no-footer">
							<div class="table-scrollable" style="overflow-x:inherit;overflow-y:inherit;">
								<table class="table table-striped table-bordered table-hover">
									<thead>
										<tr role="row">
											<th width="80"><input type="checkbox" class="group-checkable" id="allCheck">ID</th>
											<th width="150">用户名</th>
											<th width="100">预约类型</th>
											<th width="150">目的地</th>
											<th width="150">手机</th>
											<th width="200">预约日期</th>
											<th width="100">状态</th>
											<th>操作</th>
										</tr>
									</thead>
									<tbody>
									{foreach $appointmentList as $app}
										<tr dataid="{$app.id}" class="gradeX odd" role="row"> 
											<td><input type="checkbox" class="group-checkable checkbox">{$app.id}</td>
											<td><p style = "color: blue">{$app.member_name}</p>  id:({$app.member_id})</td>
											<td>
											{if $app.tourism_type == 1}跟团游
											{else if $app.tourism_type == 2}自助游
											{else if $app.tourism_type == 3}自由行
											{else if $app.tourism_type == 4}自驾游
											{else if $app.tourism_type == 5}目的地服务
											{/if}</td>
											<td>{$app.destination}</td>
											<td>{$app.phone}</td>
											<td>{$app.start_time} 至 {$app.end_time}</td>
											<td>
											{if $app.status === '0'}
											<span class="label label-warning">待确认</span>
											{else if $app.status == 1}
											<span class="label label-success">已确认</span>
											{/if}
											</td>
											<td>
											{if $app.status === '0'}
												<a href="javascript:;" class="confirm btn yellow"><i class="fa fa-check"></i> 确认</a>
											{/if}
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
			</div>
			<!--表格结束 /-->
		</div>
	</div>
	{include file='public/admincp/footer.tpl'}
<!--content-wrapper /-->
	<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/respond.min.js"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{$smarty.const.URL_MIX}metronic3.6/global/scripts/metronic.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/admin/layout/scripts/demo.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->