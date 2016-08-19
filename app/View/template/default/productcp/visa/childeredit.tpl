{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">修改子签证 <small>修改子签证</small></h3>
							<!-- 错误提醒 -->
				{if $error->hasError()}
				{foreach $error->getAll() as $e}
				<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
						<strong>错误！</strong> {array_shift($e)}
				</div>
				{/foreach}
				{/if}
				<!-- 错误提醒 /-->
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/product">商品</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/visa/list?id={$parent_id}">签证列表</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/visa/childeredit?id={$visa.id}&parent_id={$parent_id}">修改签证</a></li>
				</ul>
			</div>
			<!--表单-->
			<div class="row">
				<div class="col-md-12">
					<form action="/productcp/visa/childeredit" class="form-horizontal form-row-seperated" method="post">
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-edit"></i>内容设置
								</div>
								<div class="pull-right">
									<a href="/productcp/visa/childerlist?id={$parent_id}" class="btn default"><i class="fa fa-angle-left"></i> 返回</a>
									<button type="reset" class="btn default"><i class="fa fa-refresh"></i> 重置</button>
									<button type="submit" class="btn green"><i class="fa fa-check-circle"></i> 保存</button>
								</div>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="tab-content">
								<div class="form-body">
									<input type="hidden" name="id" value="{$visa.id}" >
									<input type="hidden" name="parent_id" value="{$parent_id}" placeholder="签证ID">
									<div class="form-group">
										<label class="control-label col-md-2">签证名称： </label>
										<div class="col-md-8">
										<input type="text" class="form-control" name="visa_name" value="{$visa.visa_name}">
										</div>
									</div>
									<div class="form-group">
	                                    <label class="col-md-2 control-label">所需资料：</label>
	                                    <div class="col-md-8">
	                                        <div class="dataTables_wrapper no-footer">
												<div class="table-scrollable">
													<table class="table table-bordered table-hover">
														<thead>
															<tr>
																<th width="200px">资料名称</th>
																<th width="150px">资料类型</th>
																<th width="150px">资料数量</th>
																<th width="400px">资料说明</th>
																<th width="300px">附件上传</th>
																<th>操作</th>
															</tr>
														</thead>
														<tbody id="tbody">
														{foreach $visa.items as $i=>$row}
															<tr data-id="{$i}">
																<td><input type="text" class="form-control" name="items[{$i}][info_name]" value="{$row.info_name}"></td>
																<td><input type="text" class="form-control" name="items[{$i}][info_type]" value="{$row.info_type}"></td>
																<td><input type="text" class="form-control" name="items[{$i}][info_total]" value="{$row.info_total}"></td>
																<td><textarea name="items[{$i}][info]" class="form-control">{$row.info_content}</textarea></td>
																<td>
																	<button type="button" class="btn green upload">上传附件 <i class="fa fa-plus"></i></button>
																	<input type="hidden" name="items[{$i}][info_file]" value="{$row.info_file}" ><a href='{$row.info_file}' class="btn blue">查看</a>
																</td>
																<td><button type="button" class="btn red delete">删除</button></td>
															</tr>
														{/foreach}
														</tbody>
													</table>
												</div>
											</div>
											<a href="javascript:;" class="btn btn-default" id="addTr"><i class="fa fa-plus"></i> 添加一行</a>
	                                    </div>
	                                </div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<!--表单 /-->
		</div>
	</div>
	<!--content-wrapper /-->
{include file='public/admincp/footer.tpl'}
<link rel="stylesheet" href="{$smarty.const.URL_JS}lib/kindeditor/themes/default/default.css" />
<script src="{$smarty.const.URL_JS}lib/kindeditor/kindeditor-all-min.js"></script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/lang/zh_CN.js"></script>
<script src="{$smarty.const.URL_JS}productcp/product/productvisa.js" ></script>
