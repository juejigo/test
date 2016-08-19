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
				
				<form method="post" class="form-horizontal form-row-seperated" action="/productcp/cate/add?area={$params.area}&parent_id={$params.parent_id}">
						<div class="portlet box blue-hoki">
								<!-- portlet-title /-->
								<div class="portlet-title">
									<div class="caption">
										分类
									</div>
								</div>
								<!-- portlet-title /-->
								
								<!-- portlet-body -->
								<div class="portlet-body form">
										<!-- form-body -->
										<div class="form-body">
										
												<div class="form-group">
													<label class="col-md-2 control-label">父分类 <span class="required">* </span></label>
													<div class="col-md-10">
															<select class="form-control" name="parent_id">
																	<option value="">无</option>
															{foreach $parentList as $parent}
																	<option {if $parent.id == $data.parent_id}selected{/if} value="{$parent.id}">{str_repeat('&nbsp;',($cateList.{$parent.id}.level)-1)}	{$parent.value}</option>
															{/foreach}
															</select>
													</div>
												</div>
												
												<div class="form-group">
													<label class="col-md-2 control-label">商品类型 <span class="required">* </span></label>
													<div class="col-md-10">
															<select class="form-control" name="type_id">
																	<option value="">无</option>
															{foreach $types as $type}
																	<option {if $type.id == $data.type_id}selected{/if} value="{$type.id}">{$type.type_name}</option>
															{/foreach}
															</select>
													</div>
												</div>
												
												<div class="form-group">
													<label class="col-md-2 control-label">分类名</label>
													<div class="col-md-10">
															<input type="text" class="form-control" name="cate_name" placeholder="" value="{$data.cate_name}">
													</div>
												</div>
												
												<div class="form-group">
													<label class="col-md-2 control-label">图片</label>
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