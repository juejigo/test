{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
	<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">编辑客服 <small>修改客服相关信息</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/usercp/member/list">会员</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/servicecp/staff/list">专属客服</a></li>
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
					<form action="/servicecp/staff/edit?id={$params.id}" class="form-horizontal form-row-seperated" method ="post">
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-edit"></i>填写信息
								</div>
								<div class="pull-right">
									<a href="/servicecp/staff/list" class="btn default"><i class="fa fa-angle-left"></i> 返回</a>
									<button type="reset" class="btn default"><i class="fa fa-refresh"></i> 重置</button>
									<button type="submit" class="btn green"><i class="fa fa-check-circle"></i> 保存</button>
								</div>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="tab-content">
								<div class="form-body">
									<div class="form-group">
										<label class="control-label col-md-2">姓名： </label>
										<div class="col-md-4"><input type="text" class="form-control" name="staff_name" value="{$staff.staff_name}" placeholder="请输入客服姓名，不超过14个字"></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">照片： </label>
										<div class="col-md-4"><input data-toggle="modal" data-target="#image_uploader" name="image" value="{$staff.avatar}" class="form-control" placeholder="点击上传照片"><a href="javascript:;" class="lookimg btn default">预览</a></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">微信：</label>
										<div class="col-md-4"><input type="text" class="form-control" value="{$staff.wx}" name="wx"></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">电话：</label>
										<div class="col-md-4"><input type="text" class="form-control" value="{$staff.phone}" name="phone"></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">自我介绍</label>
										<div class="col-md-6">
                                        	<textarea class="form-control ke" rows="4" name="introduce">{$staff.introduce}</textarea>
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
</div>
<!-- container /-->
<div class="modal fade" id="lookimg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
		  		<img id ="img" alt="" src="{$staff.avatar}">
			</div>
		</div>
	</div>
</div>
{include file='public/admincp/footer.tpl'}

<script>
$(function() {   
	$(".date-picker").datepicker({ 
		rtl: Metronic.isRTL(),
        autoclose: true
    });
	$('input[name="image"]').click(function()
	{
		imageUploader.show('brand',callback);
	})    
});
function callback(responseObject)
{
	json = $.parseJSON(responseObject.response);
	var imgDom=$("#img");
	if (json.flag == 'error')
	{
		showMessage(json.msg);
	}
	else if (json.flag == 'success')
	{
		$('input[name="image"]').val(json.url);
		imgDom.attr("src",json.url);
	}
}
//查看
$(".lookimg").click(function(){
	$("#lookimg").modal('show');
})
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>