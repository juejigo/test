{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}

<link href="{$smarty.const.URL_CSS}productcp/product/product.min.css" rel="stylesheet" type="text/css"/>

<link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/icheck/skins/all.css" rel="stylesheet" type="text/css"/>

<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/jstree/dist/themes/default/style.min.css"/>
<link href="../static/style/default/mix/metronic3.6/theme/assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>

<link rel="stylesheet" href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/select2/select2.css">
<link href="{$smarty.const.URL_MIX}metronic3.6/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/theme/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/theme/assets/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/theme/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css"/>
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
				
				<form method="post" class="form-horizontal form-row-seperated" action="/positioncp/data/add?position_id={$params.position_id}&cate_id={$params.cate_id}">
						<div class="portlet box blue-hoki">
								<!-- portlet-title /-->
								<div class="portlet-title">
									<div class="caption">
										推荐内容
									</div>
								</div>
								<!-- portlet-title /-->
								
								<!-- portlet-body -->
								<div class="portlet-body form">
										<!-- form-body -->
										<div class="form-body">
										
												<div class="form-group">
													<label class="col-md-2 control-label">标题</label>
													<div class="col-md-10">
															<input type="text" class="form-control" name="title" placeholder="" value="{$data.title}">
													</div>
												</div>
												
												<div class="form-group">
													<label class="col-md-2 control-label">图片</label>
													<div class="col-md-10">
															<input data-toggle="modal" data-target="#image_uploader" type="text" class="form-control" name="image" placeholder="" value="{$data.image}">
													</div>
												</div>
												
												<div class="form-group">
													<label class="col-md-2 control-label">关联 <span class="required">* </span></label>
													<div class="col-md-10">
															<select class="form-control" name="data_type">
																	<option value="">请选择</option>
																	<option {if $data.data_type == 'product'}selected="selected"{/if} value="product">商品</option>
																	<option {if $data.data_type == 'product_list'}selected="selected"{/if} value="product_list">商品列表</option>
																	<option {if $data.data_type == 'news'}selected="selected"{/if} value="news">新闻</option>
																	<option {if $data.data_type == 'link'}selected="selected"{/if} value="link">链接</option>
															</select>
													</div>
												</div>
												
												<div {if $data.data_type == 'product'}style="display:block;"{/if} class="product params form-group">
													<label class="col-md-2 control-label">商品ID</label>
													<div class="col-md-10">
															<input type="text" class="form-control" name="params[product_id]" placeholder="" value="{$data.params.product_id}">
													</div>
												</div>
												
												<div {if $data.data_type == 'product_list'}style="display:block;"{/if} class="product_list params form-group">
													<label class="col-md-2 control-label">区域</label>
													<div class="col-md-10">
															<select class="form-control" name="params[area]">
																	<option value="0">产品区</option>
																	<option value="1">活动区</option>
															</select>
													</div>
												</div>
												
												<div {if $data.data_type == 'product_list'}style="display:block;"{/if} class="product_list params form-group">
													<label class="col-md-2 control-label">关键字</label>
													<div class="col-md-10">
															<input type="text" class="form-control" name="params[keyword]" placeholder="" value="{$data.params.keyword}">
													</div>
												</div>
												
												<div style="display:block;" class="form-group">
													<label class="col-md-2 control-label">上架时间<input type="checkbox" id="upcheck" class="group-checkable checkbox"></label>
													<div class="col-md-10">
															<input type="text" class="form-control form_datetime" name="up_time" >
													</div>
												</div>
												
												<div style="display:block;" class="form-group">
													<label class="col-md-2 control-label">下架时间<input type="checkbox" id="downcheck" class="group-checkable checkbox"></label>
													<div class="col-md-10">
															<input type="text" class="form-control form_datetime" name="down_time">
													</div>
												</div>
												
												<div {if $data.data_type == 'product_list'}style="display:block;"{/if} class="product_list params form-group">
													<label class="col-md-2 control-label">标签</label>
													<div class="col-md-10">
															<select class="form-control" name="params[tag_id]">
																	<option value="">标签</option>
																	{foreach $tags as $tag}
																	<option {if $data.params.tag_id == $tag.id}selected="selected"{/if} value="{$tag.id}">{$tag.tag_name}</option>
																	{/foreach}
															</select>
													</div>
												</div>
												
												<div {if $data.data_type == 'product_list'}style="display:block;"{/if} class="product_list params form-group">
													<label class="col-md-2 control-label">分类</label>
													<div id="cate_selector" class="col-md-10">
															<select class="form-control" name="params[cate_id]">
																	<option value="0">分类</option>
																	{foreach $list as $cate}
																	<option style="text-indent:{($cateList.{$cate.id}.level - 1) * 10}px;" {if $cate.id == $data.params.cate_id}selected="selected"{/if} value="{$cate.id}">{$cate.value}</option>
																	{/foreach}
															</select>
													</div>
												</div>
												
												<div {if $data.data_type == 'product_list'}style="display:block;"{/if} class="product_list params form-group">
													<label class="col-md-2 control-label">出游类型</label>
													<div class="col-md-10">
															<select class="form-control" name="params[tourism_type]">
																	<option value="">出游类型</option>
																	{foreach $tourism_type as $type}
																	<option {if $data.params.tourism_type == $type.id}selected="selected"{/if} value="{$type.id}">{$type.tourism_type}</option>
																	{/foreach}
															</select>
													</div>
												</div>
												
												<div {if $data.data_type == 'product_list'}style="display:block;"{/if} class="product_list params form-group">
													<label class="col-md-2 control-label">品牌</label>
													<div id="cate_selector" class="col-md-10">
															<select class="form-control" name="params[brand_id]">
																	<option value="0">品牌</option>
																	{foreach $brands as $brand}
																	<option {if $brand.id == $data.params.brand_id}selected="selected"{/if} value="{$brand.id}">{$brand.brand_name}</option>
																	{/foreach}
															</select>
													</div>
												</div>
												
												<div {if $data.data_type == 'news'}style="display:block;"{/if} class="news params form-group">
													<label class="col-md-2 control-label">新闻ID</label>
													<div class="col-md-10">
															<input type="text" class="form-control" name="params[news_id]" placeholder="" value="{$data.params.news_id}">
													</div>
												</div>
												
												<div {if $data.data_type == 'link'}style="display:block;"{/if} class="link params form-group">
													<label class="col-md-2 control-label">链接</label>
													<div class="col-md-10">
															<input type="text" class="form-control" name="params[url]" placeholder="" value="{$data.params.url}">
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
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/jstree/dist/jstree.min.js"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/plupload/js/plupload.full.min.js"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/icheck/icheck.min.js"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>