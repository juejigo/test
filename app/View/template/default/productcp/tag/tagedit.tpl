{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
	<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">标签 <small>修改标签</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/tag">标签</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/tag">修改标签</a></li>
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
					<form action="" class="form-horizontal form-row-seperated" method ="post">
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-edit"></i>内容设置
								</div>
								<div class="pull-right">
									<a href="/productcp/tag/list" class="btn default"><i class="fa fa-angle-left"></i> 返回</a>
									<button type="reset" class="btn default"><i class="fa fa-refresh"></i> 重置</button>
									<button type="submit" class="btn green"><i class="fa fa-check-circle"></i> 保存</button>
								</div>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="tab-content">
								<div class="form-body">
									<div class="form-group">
										<label class="control-label col-md-2">标题名 <span class="required" aria-required="true">*</span></label>
										<div class="col-md-4"><input type="text" class="form-control" name="title" placeholder="请输入活动名称，不超过20个字"></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">图片 <span class="required" aria-required="true">*</span></label>
										<div class="col-md-4"><input data-toggle="modal" data-target="#image_uploader" name="image" class="form-control" placeholder="点击上传图片"></div>
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

{include file='public/admincp/footer.tpl'}
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script src="{$smarty.const.URL_JS}lib/kindeditor/kindeditor-all-min.js"></script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/themes/default/default.css"></script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/lang/zh_CN.js"></script>
<!-- 上传图片 -->

<script>
$(function() {    
    Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
	$(".date-picker").datepicker({ 
		rtl: Metronic.isRTL(),
        autoclose: true
    });
	$('input[name="image"]').click(function()
	{
		imageUploader.show('tagedit',callback);
	})
		    
});
// kindeditor
KindEditor.ready(function(K) {
    window.editor = K.create('.detail',{
        //cssPath:'/public/plugin/editor/plugins/code/prettify.css',
        uploadJson:'/imageuc/kindeditor/upload?',
        extraFileUploadParams : {
            cookie : $.cookie('session_id')
        },
        height:400,
        resizeType :1,
        allowPreviewEmoticons : true,
        allowImageUpload : true,
    });	
});
function callback(responseObject)
{
	json = $.parseJSON(responseObject.response);
	
	if (json.flag == 'error')
	{
		showMessage(json.msg);
	}
	else if (json.flag == 'success')
	{
		$('input[name="image"]').val(json.url);
	}
}
</script>
