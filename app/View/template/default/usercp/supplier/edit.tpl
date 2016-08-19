{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}

	<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">修改供应商 <small>修改供应商</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="#">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="#">会员</a><i class="fa fa-angle-right"></i></li>
					<li><a href="#">供应商管理</a><i class="fa fa-angle-right"></i></li>
					<li><a href="#">修改供应商</a></li>
				</ul>
			</div>
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
									<a href="../user/supplierList.html" class="btn default"><i class="fa fa-angle-left"></i> 返回</a>
									<button type="reset" class="btn default"><i class="fa fa-refresh"></i> 重置</button>
									<button type="submit" class="btn green"><i class="fa fa-check-circle"></i> 保存</button>
								</div>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="tab-content">
								<input type="hidden" name="id" value="{$editList['id']}" placeholder="供应商ID">
								<div class="form-body">
									<div class="form-group">
										<label class="control-label col-md-2">供应商名称： </label>
										<div class="col-md-6"><input type="text" class="form-control" name="supplier_name" value="{$editList['supplier_name']}"></div>
									</div>
                   <div class="form-group">
                      <label class="col-md-2 control-label">省市区：</label>
                      <div class="col-md-10">
										<div class="select_region" rel='["province": {$editList['province_id']},"city":{$editList['city_id']},"county":{$editList['county_id']}]'>
											<select name="province_id"
												class="form-control input-small input-inline">
												<option value="0">请选择</option>
											</select> 
											<select name="city_id"
												class="form-control input-small input-inline"><option value="0">请选择</option></select> 
											<select name="county_id"
												class="form-control input-small input-inline"><option value="0">请选择</option>
											</select>
										</div>
									</div>
                  </div>
                  <div class="form-group">
										<label class="control-label col-md-2">详细地址： </label>
										<div class="col-md-6"><input type="text" class="form-control" name="address" value="{$editList['address']}"></div>
									</div>
                  <div class="form-group">
										<label class="control-label col-md-2">联系号码： </label>
										<div class="col-md-6"><input type="text" class="form-control" name="telephone" value="{$editList['telephone']}"></div>
									</div>
                  <div class="form-group">
										<label class="control-label col-md-2">简介： </label>
										<div class="col-md-6"><textarea name="intro" class="form-control ke" rows="5">{html content=$editList['intro']}</textarea></div>
									</div>
				  <div class="form-group">
										<label class="control-label col-md-2">备注： </label>
										<div class="col-md-6"><textarea name="memo" class="form-control" rows="5">{html content=$editList['memo']}</textarea></div>
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
<script src="{$smarty.const.URL_JS}lib/kindeditor/kindeditor-all-min.js"></script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/lang/zh_CN.js"></script>
<script src="/static/style/default/js/productcp/product/regionnew.js"></script>

<script>
$(function() {
    Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
  region.init($(".select_region"));
	KindEditor.ready(function(K) {
	window.editor = K.create('.ke',{
			uploadJson:'/imageuc/kindeditor/upload?',
			resizeType :1,
			width:'100%',
			height:'300px',
			allowPreviewEmoticons : true,
			allowImageUpload : true,
	});
});
});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
