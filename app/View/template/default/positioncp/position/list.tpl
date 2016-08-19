{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}

<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">
	
			<h3 class="page-title">推荐位列表</h3>
			
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box grey-cascade">
						<div class="portlet-title">
							<div class="caption">
								推荐位列表
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
							<div class="table-toolbar">
								<div class="row">
									<div class="col-md-6">
										<div class="btn-group">
											<a class="btn green" href="/positioncp/position/add">
											新增 <i class="fa fa-plus"></i>
											</a>
										</div>
									</div>
									<div class="col-md-6">
										<div class="btn-group pull-right">
											<button class="btn dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i>
											</button>
											<ul class="dropdown-menu pull-right">
												<li>
													<a href="#">
													Print </a>
												</li>
												<li>
													<a href="#">
													Save as PDF </a>
												</li>
												<li>
													<a href="#">
													Export to Excel </a>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<table class="table table-striped table-bordered table-hover" id="sample_1">
							<thead>
							<tr>
								<th class="table-checkbox">
									<input type="checkbox" class="group-checkable"/>
								</th>
								<th>
									 推荐位名
								</th>
								<th>
									 操作
								</th>
							</tr>
							</thead>
							<tbody>
							{foreach $positionList as $i => $position}
							<tr dataid="{$position.id}" class="{if $i/2 == 1}odd{/if} gradeX">
								<td>
									<input type="checkbox" class="checkboxes" value="1"/>
								</td>
								<td>
									{$position.position_name}
								</td>
								<td>
									<a href="/positioncp/position/edit?id={$position.id}" class="btn green">编辑 <i class="glyphicon glyphicon-edit"></i></a>
									<a href="/positioncp/data/list?position_id={$position.id}" class="btn green">查看内容 <i class="glyphicon glyphicon-list-alt"></i></a>
								</td>
							</tr>
							{/foreach}
							</tbody>
							</table>
						</div>
					</div>
					<!-- END EXAMPLE TABLE PORTLET-->
				</div>
			</div>
			
	</div>
</div>
<!-- END CONTENT -->

{include file='public/admincp/footer.tpl'}

<!-- 添加推荐位 -->
<div id="position_form" class="modal fade">
		<div class="modal-dialog">
				<div class="modal-content">
						<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">添加推荐位</h4>
						</div>
						
						<div class="modal-body">
						
						<form class="form-horizontal form-row-seperated" action="/productcp/position/ajax?op=addposition" method="post">
								<!-- form-body -->
								<div class="form-body">
										
										<div class="form-group">
											<label class="col-md-3 control-label">推荐位名</label>
											<div class="col-md-9">
													<input type="text" class="form-control" name="position_name" placeholder="" value="">
											</div>
										</div>
								
								</div>
								<!-- form-body /-->
								
								<!-- form-action -->
								<div class="form-actions fluid">
									<div class="row">
										<div class="col-md-offset-2 col-md-9">
												<button id="position_submit" class="btn yellow" type="button">提交</button>
										</div>
									</div>
								</div>
								<!-- form-action /-->
						</form>
						
						</div>
				</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- 添加推荐位 /-->