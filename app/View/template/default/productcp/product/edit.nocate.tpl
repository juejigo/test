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
				
				<form method="post" class="form-horizontal form-row-seperated" action="/productcp/product/edit?id={$params.id}">
						<div class="portlet">
								<!-- portlet-title /-->
								<div class="portlet-title">
									<div class="caption">
										商品
									</div>
								</div>
								<!-- portlet-title /-->
								
								<!-- portlet-body -->
								<div class="portlet-body form">
								
										<!-- 标签 -->
										<ul class="nav nav-tabs" role="tablist">
												<li role="presentation" class="active"><a href="#base_pane" aria-controls="base_pane" role="tab" data-toggle="tab">基本信息</a></li>
												<li role="presentation"><a href="#image_pane" aria-controls="image_pane" role="tab" data-toggle="tab">图片</a></li>
												<li role="presentation"><a href="#attrs_pane" aria-controls="attrs_pane" role="tab" data-toggle="tab">属性</a></li>
												<li role="presentation"><a href="#search_pane" aria-controls="search_pane" role="tab" data-toggle="tab">搜索相关</a></li>
												<li role="presentation"><a href="#tag_pane" aria-controls="tag_pane" role="tab" data-toggle="tab">商品标签</a></li>
										</ul>
										<!-- 标签 /-->
										
										<!-- Tab panes -->
										<div class="tab-content">
												<div role="tabpanel" class="tab-pane active" id="base_pane">
												<!-- form-body -->
												<div class="form-body">
														<div class="form-group">
																<label class="col-md-2 control-label">分类 <span class="required">* </span></label>
																<div class="col-md-10">{$cate.cate_name}</div>
															</div>
															
															<div class="form-group">
																<label class="col-md-2 control-label">品牌</label>
																<div class="col-md-10">
																		<select class="form-control" name="brand_id">
																				<option value="0">不选择</option>
																				{foreach $brandList as $brand}
																				<option {if $brand.id == $data.brand_id}selected="selected"{/if} value="{$brand.id}">{$brand.brand_name}</option>
																				{/foreach}
																		</select>
																</div>
															</div>
															
															<div class="form-group">
																<label class="col-md-2 control-label">商家编码</label>
																<div class="col-md-10">
																		<input type="text" class="form-control" name="sn" placeholder="" value="{$data.sn}">
																</div>
															</div>
															
															<div class="form-group">
																<label class="col-md-2 control-label">货号</label>
																<div class="col-md-10">
																		<input type="text" class="form-control" name="art" placeholder="" value="{$data.art}">
																</div>
															</div>
															
															<div class="form-group">
																<label class="col-md-2 control-label">商品名 <span class="required">* </span></label>
																<div class="col-md-10">
																		<input type="text" class="form-control" name="product_name" placeholder="" value="{$data.product_name}">
																</div>
															</div>
															
															<div class="form-group">
																<label class="col-md-2 control-label">销售价 <span class="required">* </span></label>
																<div class="col-md-10">
																		<input type="text" class="form-control" name="price" placeholder="" value="{$data.price}">
																</div>
															</div>
															
															<div class="form-group">
																<label class="col-md-2 control-label">市场价</label>
																<div class="col-md-10">
																		<input type="text" class="form-control" name="mktprice" placeholder="" value="{$data.mktprice}">
																</div>
															</div>
															
															<div class="form-group">
																<label class="col-md-2 control-label">成本价 <span class="required">* </span></label>
																<div class="col-md-10">
																		<input type="text" class="form-control" name="cost_price" placeholder="" value="{$data.cost_price}">
																</div>
															</div>
															
															<div class="form-group">
																<label class="col-md-2 control-label">库存 <span class="required">* </span></label>
																<div class="col-md-10">
																		<input type="text" class="form-control" name="stock" placeholder="" value="{$data.stock}">
																</div>
															</div>
															
															<div class="form-group">
																<label class="col-md-2 control-label">规格</label>
																<div id="specs" class="col-md-10">
																		<div id="spec-hd">
																				<label class="radio-inline">
																				<input type="radio" name="open_spec" value="0" {if empty($data.items) || count($data.items) == 1}checked="checked"{/if}> 统一规格
																				</label>
																				<label class="radio-inline">
																				<input type="radio" name="open_spec" value="1" {if !empty($data.items) && count($data.items) > 1}checked="checked"{/if}> 多种规格
																				</label>
																		</div>
																		<div id="spec-bd" {if !empty($data.items) && count($data.items) > 1}style="display:block;"{/if}>{include file='productcp/product/specs.tpl'}</div>
																</div>
															</div>
															
															<div class="form-group">
																<label class="col-md-2 control-label">详情</label>
																<div class="col-md-10">
																		<textarea id="detail" name="detail" class="form-control">{html content=$data.detail}</textarea>
																</div>
															</div>
															
															<div class="form-group">
																	<label class="col-md-2 control-label">时间: <span class="required">* </span></label>
																	<div class="col-md-10">
																			<div data-date-format="mm/dd/yyyy" data-date="10/11/2012" class="input-group input-large date-picker input-daterange">
																			<input type="text" name="up_time" class="datepicker form-control" value="{$data.up_time}">
																			<span class="input-group-addon">
																			至 </span>
																			<input type="text" name="down_time" class="datepicker form-control" value="{$data.down_time}">
																			</div>
																			<span class="help-block">商品上下架时间</span>
																	</div>
															</div>
												
												</div>
												<!-- form-body /-->
												</div>
												
												<div role="tabpanel" class="tab-pane" id="image_pane">
												<!-- form-body -->
												<div class="form-body">
														<div class="form-group">
															<label class="col-md-2 control-label">图片</label>
															<div class="col-md-10">
																	<a data-toggle="modal" data-target="#image_uploader" name="image" class="upload btn green">上传</a>
															</div>
														</div>
														
														
														<div id="thumbnails" class="row">
														{foreach $imageList as $image}
														<div class="col-md-2">
																<div dataid="{$image.id}" data="{$image.image}" class="thumbnail {if !empty($data.image) && $data.image == $image.image}defaultimage{/if}">
																		<img src="{thumbpath source=$image.image width=220}">
																		<div class="caption">
																		<p><a href="javascript:;" class="image-default btn btn-default" role="button">设为默认</a> <a href="javascript:;" class="image-delete btn btn-primary" role="button">删除</a></p>
																		</div>
																</div>
														</div>
														{/foreach}
														</div>
												
												</div>
												<!-- form-body /-->
												</div>
												
												<div role="tabpanel" class="tab-pane" id="attrs_pane">
												<!-- form-body -->
												<div id="attrs" class="form-body">
														{include file='productcp/product/attrs.tpl'}
												</div>
												<!-- form-body /-->
												</div>
												
												<div role="tabpanel" class="tab-pane" id="search_pane">
												<!-- form-body -->
												<div class="form-body">
														<div class="form-group">
															<label class="col-md-2 control-label">标题</label>
															<div class="col-md-10">
																	<input type="text" class="form-control" name="title" placeholder="" value="{$data.title}">
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-md-2 control-label">关键字</label>
															<div class="col-md-10">
																	<input type="text" class="form-control" name="keywords" placeholder="" value="{$data.keywords}">
															</div>
														</div>
														
														<div class="form-group">
															<label class="col-md-2 control-label">描述</label>
															<div class="col-md-10">
																	<input type="text" class="form-control" name="desc" placeholder="" value="{$data.desc}">
															</div>
														</div>
												
												</div>
												<!-- form-body /-->
												</div>
												
												<div role="tabpanel" class="tab-pane" id="tag_pane">
												<!-- form-body -->
												<div class="form-body">
														<div class="form-group">
															<label class="col-md-2 control-label">商品标签</label>
															<div class="col-md-10">
															{foreach $tags as $tag}
																	<label><input type="checkbox" name="tags[]" value="{$tag.id}" {if !empty($data.tags) && in_array($tag.id,$data.tags)}checked="checked"{/if} /> {$tag.tag_name}</label>
															{/foreach}
															</div>
														</div>
												
												</div>
												<!-- form-body /-->
												</div>
										
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

<script type="text/javascript">var pagevar = { 'product_id' : {$params.id},'cate_id' : {$cate.id} }</script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/kindeditor-all-min.js"></script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/themes/default/default.css"></script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/lang/zh_CN.js"></script>