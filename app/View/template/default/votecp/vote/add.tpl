{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
	<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">新增投票 <small>新建一个投票活动</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/votecp/vote">投票</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/votecp/vote/add">新增投票</a></li>
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
									<a href="/votecp/vote/list" class="btn default"><i class="fa fa-angle-left"></i> 返回</a>
									<button type="reset" class="btn default"><i class="fa fa-refresh"></i> 重置</button>
									<button type="submit" class="btn green"><i class="fa fa-check-circle"></i> 保存</button>
								</div>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="tab-content">
								<div class="form-body">
									<div class="form-group">
										<label class="control-label col-md-2">投票活动名称 <span class="required" aria-required="true">*</span></label>
										<div class="col-md-4"><input type="text" class="form-control" name="votename" placeholder="请输入活动名称，不超过20个字"></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">上传横幅 <span class="required" aria-required="true">*</span></label>
										<div class="col-md-4"><input data-toggle="modal" data-target="#image_uploader" name="image" class="form-control" placeholder="点击上传图片"></div>
									</div>
									<!-- <div class="form-group">
										<label class="control-label col-md-2">横幅跳转地址</label>
										<div class="col-md-4"><input type="text" class="form-control" name="bannerUrl" placeholder="横幅跳转地址"></div>
									</div> -->
									<div class="form-group">
										<label class="control-label col-md-2">参赛规则</label>
										<div class="col-md-5">
											<textarea id="detail" name="detail" class="form-control detail"></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">活动简介</label>
										<div class="col-md-5">
											<textarea id="intro" name="intro" class="form-control"></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">活动按钮名</label>
										<div class="col-md-5">
											<textarea id="vote_btn" name="vote_btn" class="form-control"></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">活动获奖情况</label>
										<div class="col-md-5">
											<textarea id="awards" name="awards" class="form-control detail"></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">报名时间 <span class="required" aria-required="true">*</span></label>
										<div class="col-md-4">
											<div data-date-format="yyyy-mm-dd" class="input-group input-large date-picker input-daterange">
												<input type="text" name="signup_begin_time" class="form-control" value="" placeholder="开始时间">
												<span class="input-group-addon">至</span>
												<input type="text" name="signup_end_time" class="form-control" value="" placeholder="结束时间">
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">投票时间 <span class="required" aria-required="true">*</span></label>
										<div class="col-md-4">
											<div data-date-format="yyyy-mm-dd" class="input-group input-large date-picker input-daterange">
												<input type="text" name="vote_begin_time" class="form-control" value="" placeholder="开始时间">
												<span class="input-group-addon">至</span>
												<input type="text" name="vote_end_time" class="form-control" value="" placeholder="结束时间">
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">每人总投票数</label>
										<div class="col-md-4"><input type="text" class="form-control" name="all_vote" placeholder="每人总投票数" value="0">
										<span class="help-block">每个粉丝总的投票数，0表示不限制，默认为0</span></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">每天投票数</label>
										<div class="col-md-4"><input type="text" class="form-control" name="day_vote" placeholder="每天投票数" value="0">
										<span class="help-block">每个粉丝每天的投票数，0表示不限制，默认为0</span></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">1对1每天投票数</label>
										<div class="col-md-4"><input type="text" class="form-control" name="one_to_vote" placeholder="1对1每天投票数" value="0">
										<span class="help-block">每个粉丝每天对单个参赛对象的投票数，0表示不限制，默认为0</span></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">是否需要关注</label>
										<input type="radio" name="subscribe" value="1" checked="checked" />需要 
                                        <input type="radio" name="subscribe" value="0" />不需要 
										<!-- <div class="col-md-4"><input type="text" class="form-control" name="subscribe" placeholder="是否需要关注" value="0">
										<span class="help-block">1表示需要，0表示不需要，默认为1</span></div> -->
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">是否能够报名</label>
										<!-- <div class="col-md-4"><input type="text" class="form-control" name="allow_post" placeholder="是否可以报名" value="0"> -->
										<input type="radio" name="allow_post" value="1" checked="checked" />可以
                                        <input type="radio" name="allow_post" value="0" />不可以
										<!-- <span class="help-block">1表示可以，0表示不可以，默认为1</span></div> -->
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">选手是否需要审核</label>
										<!-- <div class="col-md-4"><input type="text" class="form-control" name="allow_post" placeholder="是否需要审核" value="0"> -->
										<input type="radio" name="player_auth" value="1" checked="checked" />需要 
                                        <input type="radio" name="player_auth" value="0" />不需要 
	
										<!-- <span class="help-block">1表示可以，0表示不可以，默认为1</span></div> -->
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">评论是否需要审核</label>
										<input type="radio" name="comment_auth" value="1" checked="checked" />需要 
                                        <input type="radio" name="comment_auth" value="0" />不需要 
	
										<!-- <span class="help-block">1表示可以，0表示不可以，默认为1</span></div> -->
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">投票是否需要24小时限制</label>
										<input type="radio" name="day_auth" value="1"  />需要 
                                        <input type="radio" name="day_auth" value="0" checked="checked"/>不需要 
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">排名显示</label>
										<div class="col-md-2">
											<div class="input-group">
												<span class="input-group-addon">前</span>
												<input type="text" class="form-control" name="rank_num" placeholder="显示前多少名" value="10">
												<span class="input-group-addon">名</span>
											</div>
										<span class="help-block">前端排名页面显示排名位数</span></div>
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
		imageUploader.show('vote',callback);
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
