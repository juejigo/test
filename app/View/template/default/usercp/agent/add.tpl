{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
<link rel="stylesheet" href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/select2/select2.css">

	<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">新增代理商 <small>添加一个新的代理商</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="#">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="#">会员</a><i class="fa fa-angle-right"></i></li>
					<li><a href="#">代理商管理</a><i class="fa fa-angle-right"></i></li>
					<li><a href="#">新增代理商</a></li>
				</ul>
			</div>
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
			<!--表单-->
			<div class="row">
				<div class="col-md-12">
					<form action="" class="form-horizontal form-row-seperated" method="post">
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-edit"></i>内容设置
								</div>
								<div class="pull-right">
									<a href="../user/agentList.html" class="btn default"><i class="fa fa-angle-left"></i> 返回</a>
									<button type="reset" class="btn default"><i class="fa fa-refresh"></i> 重置</button>
									<button type="submit" class="btn green"><i class="fa fa-check-circle"></i> 保存</button>
								</div>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="tab-content">
								<div class="form-body">
									<div class="form-group">
										<label class="control-label col-md-2">代理商名称： </label>
										<div class="col-md-6"><input type="text" class="form-control" name="agent_name"></div>
									</div>
								
									<div class="form-group">
									<label class="control-label col-md-2">代理城市： </label>
									<div class="col-md-4">
										<select name="agent_city_id" class="form-control select2"><option
												value="0">请选择</option> 
											{foreach $city as $v}
											<option value="{$v['id']}">{$v['region_name']}</option>
											{/foreach}
										</select>
									</div>
									</div>
                  <div class="form-group">
										<label class="control-label col-md-2">公司名称： </label>
										<div class="col-md-6"><input type="text" class="form-control" name="company_name"></div>
									</div>
                  <div class="form-group">
                      <label class="col-md-2 control-label">省市区：</label>
                      <div class="col-md-10">
                          <div class="select_region">
                              <select name="province_id" class="form-control input-small input-inline"><option value="">请选择</option></select>
                              <select name="city_id" class="form-control input-small input-inline"><option value="">请选择</option></select>
                              <select name="county_id" class="form-control input-small input-inline"><option value="">请选择</option></select>
                          </div>
                      </div>
                  </div>
                  <div class="form-group">
										<label class="control-label col-md-2">详细地址： </label>
										<div class="col-md-6"><input type="text" class="form-control" name="address"></div>
									</div>
                  <div class="form-group">
										<label class="control-label col-md-2">联系人： </label>
										<div class="col-md-6"><input type="text" class="form-control" name="linkman"></div>
									</div>
                  <div class="form-group">
										<label class="control-label col-md-2">联系手机： </label>
										<div class="col-md-6"><input type="text" class="form-control" name="linkman_mobile"></div>
									</div>
                  <div class="form-group">
										<label class="control-label col-md-2">联系座机： </label>
										<div class="col-md-6"><input type="text" class="form-control" name="telephone"></div>
									</div>
                  <div class="form-group">
										<label class="control-label col-md-2">备注： </label>
										<div class="col-md-6"><textarea name="memo" class="form-control" rows="5"></textarea></div>
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
</div>
<!-- container /-->
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="page-footer-inner"> 2014 &copy; Metronic by keenthemes.</div>
	<div class="scroll-to-top">
		<i class="icon-arrow-up"></i>
	</div>
</div>
<!-- END FOOTER -->
{include file='public/admincp/footer.tpl'}
<script src="/static/style/default/js/productcp/product/regionnew.js"></script>

<!-- END PAGE LEVEL SCRIPTS -->
<script>
$(function() {
    Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
  region.init($(".select_region"));
  $(".select2").select2();
});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
