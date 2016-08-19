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
				
				<form method="post" class="form-horizontal form-row-seperated" action="/productcp/type/edit?id={$params.id}">
						<div class="portlet">
								<!-- portlet-title /-->
								<div class="portlet-title">
									<div class="caption">
										商品类型
									</div>
								</div>
								<!-- portlet-title /-->
								
								<!-- portlet-body -->
								<div class="portlet-body form">
								
										<!-- 标签 -->
										<ul class="nav nav-tabs" role="tablist">
												<li role="presentation" class="active"><a href="#base_pane" aria-controls="base_pane" role="tab" data-toggle="tab">名称</a></li>
												<li role="presentation"><a href="#attrs_pane" aria-controls="attrs_pane" role="tab" data-toggle="tab">属性</a></li>
												<li role="presentation"><a href="#spec_pane" aria-controls="spec_pane" role="tab" data-toggle="tab">规格</a></li>
										</ul>
										<!-- 标签 /-->
										
										<!-- Tab panes -->
										<div class="tab-content">
												<div role="tabpanel" class="tab-pane active" id="base_pane">
														<!-- form-body -->
														<div class="form-body">
															
																<div class="form-group">
																	<label class="col-md-2 control-label">类型名称 <span class="required">* </span></label>
																	<div class="col-md-10">
																			<input type="text" class="form-control" name="type_name" placeholder="" value="{$data.type_name}">
																	</div>
																</div>
												
														</div>
														<!-- form-body /-->
												</div>
												<div role="tabpanel" class="tab-pane" id="attrs_pane">
												
														<div id="tab_images_uploader_container" class="text-align-reverse margin-bottom-10">
																<a id="add_attr" href="javascript:;" class="btn yellow">
																<i class="fa fa-plus"></i> 增加
																</a>
														</div>
														<!-- form-body -->
														<table id="attr_table" class="table table-bordered table-hover">
														<thead>
														<tr role="row" class="heading">
																<th width="20%">属性名</th>
																<th width="80%">选项</th>
														</tr>
														</thead>
														<tbody>
														{foreach $data.attrs as $i => $attr}
														<tr class="attr" index="{$i}">
																<td><input type="text" class="form-control" name="attrs[{$i}][name]" value="{$attr.name}"></td>
																<td><input type="text" class="form-control" name="attrs[{$i}][options]" value="{implode(',',$attr.options)}"></td>
														</tr>
														{/foreach}
														</tbody>
														</table>
														<!-- form-body /-->
												</div>
												<div role="tabpanel" class="tab-pane" id="spec_pane">
												<!-- form-body -->
												<div class="form-body">
														<div class="form-group">
															<label class="col-md-2 control-label">规格一</label>
															<div class="col-md-10">
																	<select class="form-control" name="spec_1">
																			<option value="">无</option>
																			{foreach $specList as $spec}
																			<option {if $data.spec_1 == $spec.id}selected{/if} value="{$spec.id}">{$spec.spec_name}</option>
																			{/foreach}
																	</select>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-md-2 control-label">规格二</label>
															<div class="col-md-10">
																	<select class="form-control" name="spec_2">
																			<option value="">无</option>
																			{foreach $specList as $spec}
																			<option {if $data.spec_2 == $spec.id}selected{/if} value="{$spec.id}">{$spec.spec_name}</option>
																			{/foreach}
																	</select>
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-md-2 control-label">规格三</label>
															<div class="col-md-10">
																	<select class="form-control" name="spec_3">
																			<option value="">无</option>
																			{foreach $specList as $spec}
																			<option {if $data.spec_3 == $spec.id}selected{/if} value="{$spec.id}">{$spec.spec_name}</option>
																			{/foreach}
																	</select>
															</div>
														</div>
												
												</div>
												
										</div>
										<!-- form-body /-->
										</div>
										
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