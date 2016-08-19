{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}

	<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">选手列表 <small>编辑参赛选手</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/votecp/vote">投票</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/votecp/player/list?vp_id={$params.vp_id}">选手列表</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/votecp/player/edit?id={$params.id}&vp_id={$params.vp_id}">编辑选手</a></li>
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
									<i class="fa fa-edit"></i>填写信息
								</div>
								<div class="pull-right">
									<a href="/votecp/player/list?vp_id={$vp_id}&status=all" class="btn default"><i class="fa fa-angle-left"></i> 返回</a>
									<button type="reset" class="btn default"><i class="fa fa-refresh"></i> 重置</button>
									<button type="submit" class="btn green"><i class="fa fa-check-circle"></i> 保存</button>
								</div>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="tab-content">
								<div class="form-body">
									<div class="form-group">
										<label class="control-label col-md-2">真实姓名 <span class="required" aria-required="true">*</span></label>
										<div class="col-md-4"><input type="text" class="form-control" name="name" placeholder="请输入真实姓名" value="{$editList[0]['name']}"></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">个人照片 <span class="required" aria-required="true">*</span></label>
										<div class="col-md-4"><input data-toggle="modal" data-target="#image_uploader" name="image" class="form-control" placeholder="点击添加图片"  value="{$editList[0]['image']}"></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">参赛宣言</label>
										<div class="col-md-4"><input type="text" class="form-control" name="declaration" placeholder="参赛宣言" value="{$editList[0]['declaration']}"></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">自我介绍</label>
										<div class="col-md-5">
											<textarea id="detail" name="introduction" class="form-control">{html content=$editList[0].introduction}</textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">选手票数</label>
										<div class="col-md-2"><input type="text" class="form-control" name="vote_num" placeholder="选手票数" value="{$editList[0]['vote_num']}">
										<span class="help-block">可以手动设置选手得票数</span></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">联系方式</label>
										<div class="col-md-2"><input type="text" class="form-control" name="phone" placeholder="联系方式" value="{$editList[0]['phone']}"></div>
									</div>
								
									<div class="form-group">
										<label class="control-label col-md-2">审核</label>
										<div class="col-md-2">				
											<select class="form-control" name="audit">
												<option {if $editList[0].status == 0}selected="selected"{/if} value="0">待审核</option>
												<option {if $editList[0].status == 1}selected="selected"{/if} value="1">通过</option>
												<option {if $editList[0].status == -1}selected="selected"{/if} value="-1">不通过</option>												
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">审核失败理由</label>
										<div class="col-md-4">
											<textarea name="nopass"  class="form-control">{html content=$editList[0].nopass}</textarea>
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

{include file='public/admincp/footer.tpl'}
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!-- END PAGE LEVEL SCRIPTS -->
<script src="{$smarty.const.URL_JS}lib/kindeditor/kindeditor-all-min.js"></script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/themes/default/default.css"></script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/lang/zh_CN.js"></script>
<!-- 上传图片 -->

<!-- 上传图片 /-->
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
		imageUploader.show('vote',callback);
	})
});
// kindeditor
KindEditor.ready(function(K) {
    window.editor = K.create('#detail',{
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
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
