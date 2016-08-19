{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">添加签证 <small>添加一个新的签证</small></h3>
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
					<li><a href="/productcp/visa/list">签证列表</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/visa/edit?id={$visa.id}">修改签证</a></li>
				</ul>
			</div>
			<!--表单-->
			<div class="row">
				<div class="col-md-12">
					<form action="/productcp/visa/edit" class="form-horizontal form-row-seperated" method="post">
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-edit"></i>内容设置
								</div>
								<div class="pull-right">
									<a href="/productcp/visa/list" class="btn default"><i class="fa fa-angle-left"></i> 返回</a>
									<button type="reset" class="btn default"><i class="fa fa-refresh"></i> 重置</button>
									<button type="submit" class="btn green"><i class="fa fa-check-circle"></i> 保存</button>
								</div>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="tab-content">
								<div class="form-body">
									<div class="form-group">
									<input type="text" class="form-control" name="id" value="{$visa.id}" style="display:none;">
										<label class="control-label col-md-2">签证名称： </label>
										<div class="col-md-8"><input type="text" class="form-control" name="visa_name" value="{$visa.visa_name}"></div>
									</div>
									<div class="form-group">
	                                    <label class="col-md-2 control-label">备注：</label>
	                                    <div class="col-md-8">
	                                        <textarea class="form-control ke" name="content" rows="10">{html content=$visa.content}</textarea>
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


<script src="{$smarty.const.URL_JS}lib/kindeditor/kindeditor-all-min.js"></script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/lang/zh_CN.js"></script>
<script src="{$smarty.const.URL_JS}productcp/product/productvisa.js" ></script>
