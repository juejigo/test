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
				
				<form method="post" class="form-horizontal form-row-seperated" action="/positioncp/position/edit?id={$params.id}">
						<div class="portlet box blue-hoki">
								<!-- portlet-title /-->
								<div class="portlet-title">
									<div class="caption">
										推荐位
									</div>
								</div>
								<!-- portlet-title /-->
								
								<!-- portlet-body -->
								<div class="portlet-body form">
										<!-- form-body -->
										<div class="form-body">
											
											
											
											<div class="form-group">
													<label class="col-md-2 control-label">推荐组</label>
													<div class="col-md-10">
												 
											<select class="form-control form-filter input-sm" name="group_id">
												<option value="">请选择</option>
												
											{foreach $grouplist as $group}	
												<option value="{$group['id']}" {if $data.group_id ==$group['id']} selected  {/if}>{$group['group_name']}</option>
				 							{/foreach}
											</select>															 
													 
													</div>
												</div>														
											
											
											
												
												<div class="form-group">
													<label class="col-md-2 control-label">推荐位名</label>
													<div class="col-md-10">
															<input type="text" class="form-control" name="position_name" placeholder="" value="{$data.position_name}">
													</div>
												</div>
												
												<div class="form-group">
													<label class="col-md-2 control-label">备注</label>
													<div class="col-md-10">
															<input type="text" class="form-control" name="memo" placeholder="" value="{$data.memo}">
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

{include file='public/admincp/footer.tpl'}