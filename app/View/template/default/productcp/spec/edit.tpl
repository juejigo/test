{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}

<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">

<!-- BEGIN PAGE CONTENT-->
<div class="row">
		<div class="col-md-12">

				{if $error->hasError()}
				{foreach $error->getAll() as $e}
				<div class="alert alert-error">
						<button type="button" class="close">&times;</button>
						<strong>错误！</strong> {array_shift($e)}
				</div>
				{/foreach}
				{/if}
				
				<form method="post" class="form-horizontal form-row-seperated" action="/productcp/spec/edit?id={$params.id}">
						<div class="portlet box blue-hoki">
								<!-- portlet-title /-->
								<div class="portlet-title">
									<div class="caption">
										规格
									</div>
								</div>
								<!-- portlet-title /-->
								
								<!-- portlet-body -->
								<div class="portlet-body form">
										<!-- form-body -->
										<div class="form-body">
												
												<div class="form-group">
													<label class="col-md-2 control-label">规格名</label>
													<div class="col-md-10">
															<input type="text" class="form-control" name="spec_name" placeholder="" value="{$data.spec_name}">
													</div>
												</div>
										
										</div>
										<!-- form-body /-->
										
										<!-- form-action -->
										<div class="form-actions fluid">
											<div class="row">
												<div class="col-md-offset-2 col-md-9">
													<button class="btn yellow" type="submit">提交</button>
												</div>
											</div>
										</div>
										<!-- form-action /-->
								</div>
								<!-- portlet-body /-->
						</div>
				</form>
		</div>
</div>
<!-- END PAGE CONTENT-->

<div class="row">
		<div class="col-md-12 col-sm-12">
			<div class="portlet grey-cascade box">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-cogs"></i>规格值
					</div>
					<div class="actions">
						<a data-toggle="modal" data-target="#value_form" href="javascript:;" class="btn btn-default btn-sm">
						<i class="fa fa-pencil"></i> 增加 </a>
					</div>
				</div>
				<div class="portlet-body">
					<div class="table-responsive">
						<table class="table table-hover table-bordered table-striped">
						<thead>
						<tr>
							<th>
								 规格值
							</th>
							<th>
								 排序字符
							</th>
						</tr>
						</thead>
						<tbody>
						{foreach $valueList as $i => $value}
						<tr dataid="{$value.id}" class="{if $i/2 == 1}odd{/if} gradeX">
							<td><a class="specval" data-toggle="modal" data-target="#edit_form" href="javascript:;">{$value.value}</a></td>
							<td>{$value.letter}</td>
						</tr>
						{/foreach}
						</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
</div>

{include file='public/admincp/footer.tpl'}

<!-- 增加规格值 -->
<div id="value_form" class="modal fade">
		<div class="modal-dialog">
				<div class="modal-content">
						<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">增加规格值</h4>
						</div>
						
						<div class="modal-body">
						
						<form class="form-horizontal form-row-seperated" action="/productcp/spec/ajax?op=addvalue" method="post">
								<!-- form-body -->
								<div class="form-body">
										
										<div class="form-group">
											<label class="col-md-3 control-label">规格值</label>
											<div class="col-md-9">
													<input type="text" class="form-control" name="value" placeholder="" value="">
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-md-3 control-label">排序字符</label>
											<div class="col-md-9">
													<input type="text" class="form-control" name="letter" placeholder="" value="">
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-md-3 control-label">备注</label>
											<div class="col-md-9">
													<input type="text" class="form-control" name="memo" placeholder="" value="">
											</div>
										</div>
								
								</div>
								<!-- form-body /-->
								
								<!-- form-action -->
								<div class="form-actions fluid">
									<div class="row">
										<div class="col-md-offset-2 col-md-9">
												<input type="hidden" name="spec_id" value="{$params.id}" />
												<button id="value_submit" class="btn yellow" type="button">提交</button>
										</div>
									</div>
								</div>
								<!-- form-action /-->
						</form>
						
						</div>
				</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- 增加规格值 /-->

<!-- 修改规格值 -->
<div id="edit_form" class="modal fade">
		<div class="modal-dialog">
				<div class="modal-content">
						<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">增加规格值</h4>
						</div>
						
						<div class="modal-body">
						
						<form class="form-horizontal form-row-seperated" action="/productcp/spec/ajax?op=editvalue" method="post">
								<!-- form-body -->
								<div class="form-body">
										
										<div class="form-group">
											<label class="col-md-3 control-label">排序字符</label>
											<div class="col-md-9">
													<input type="text" class="form-control" name="letter" placeholder="" value="">
											</div>
										</div>
										
										<div class="form-group">
											<label class="col-md-3 control-label">备注</label>
											<div class="col-md-9">
													<input type="text" class="form-control" name="memo" placeholder="" value="">
											</div>
										</div>
								
								</div>
								<!-- form-body /-->
								
								<!-- form-action -->
								<div class="form-actions fluid">
									<div class="row">
										<div class="col-md-offset-2 col-md-9">
												<input type="hidden" name="id" value="" />
												<button id="edit_submit" class="btn yellow" type="button">提交</button>
										</div>
									</div>
								</div>
								<!-- form-action /-->
						</form>
						
						</div>
				</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- 修改规格值 /-->