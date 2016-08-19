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
				
				<form method="post" class="form-horizontal form-row-seperated" action="/newscp/news/edit?id={$params.id}">
						<div class="portlet">
								<!-- portlet-title /-->
								<div class="portlet-title">
									<div class="caption">
										新闻
									</div>
								</div>
								<!-- portlet-title /-->
								
								<!-- portlet-body -->
								<div class="portlet-body form">
										
										<!-- form-body -->
										<div class="form-body">
													<div class="form-group">
														<label class="col-md-2 control-label">分类 <span class="required">* </span></label>
														<div class="col-md-10">
																<select class="form-control" name="cate_id">
																		<option value="0">请选择分类</option>
																		{foreach $list as $cate}
																		<option style="text-indent:{($cateList.{$cate.id}.level - 1) * 10}px;" {if $cate.id == $data.cate_id}selected="selected"{/if} value="{$cate.id}">{$cate.value}</option>
																		{/foreach}
																</select>
														</div>
													</div>
													
													<div class="form-group">
														<label class="col-md-2 control-label">标题 <span class="required">* </span></label>
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
														<label class="col-md-2 control-label">详情</label>
														<div class="col-md-10">
																<textarea id="content" name="content" class="form-control">{html content=$data.content}</textarea>
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

<script type="text/javascript">var pagevar = { 'news_id' : 0 }</script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/kindeditor-all-min.js"></script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/themes/default/default.css"></script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>