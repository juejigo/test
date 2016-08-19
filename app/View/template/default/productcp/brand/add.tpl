{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}

<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">

<!-- BEGIN PAGE CONTENT-->
<div class="row">
		<div class="col-md-12">
				<!-- 错误提醒 -->
				{if $error->hasError()}
				{foreach $error->getAll() as $e}
				<div class="alert alert-error">
						<button type="button" class="close">&times;</button>
						<strong>错误！</strong> {array_shift($e)}
				</div>
				{/foreach}
				{/if}
				<!-- 错误提醒 /-->
				
				<form method="post" class="form-horizontal form-row-seperated" action="/productcp/brand/add">
						<div class="portlet box blue-hoki">
								<!-- portlet-title /-->
								<div class="portlet-title">
									<div class="caption">
										品牌
									</div>
								</div>
								<!-- portlet-title /-->
								
								<!-- portlet-body -->
								<div class="portlet-body form">
										<!-- form-body -->
										<div class="form-body">
										
												<div class="form-group">
													<label class="col-md-2 control-label">品牌名 <span class="required">* </span></label>
													<div class="col-md-10">
															<input type="text" class="form-control" name="brand_name" placeholder="" value="{$data.brand_name}">
													</div>
												</div>
												
												<div class="form-group">
													<label class="col-md-2 control-label">logo</label>
													<div class="col-md-10">
															<input data-toggle="modal" data-target="#image_uploader" type="text" class="form-control" name="image" placeholder="" value="{$data.image}">
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