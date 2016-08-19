 {include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
 <link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen"> 
 <script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/jquery-1.8.3.min.js" charset="UTF-8"></script>
 <script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
 <script type="text/javascript" src="{$smarty.const.URL_WEB}js/public/region.js" charset="UTF-8" p="{$province_id}" c1="{$city_id}" c2="{$county_id}"  id="js_region">	 </script>
 
	<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">数据备份 <small>备份整个系统的数据库结构和数据</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/systemcp/setting/config">设置</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/systemcp/database">数据备份</a></li>
				</ul>
			</div>
			<!--内容-->
			<div class="note note-success">
				<p>说明：您可以在此处备份整个系统的数据库结构和数据，一旦系统数据意外损坏，您可以通过数据库恢复功能恢复所备份的数据。</p>
				<p>备份过程中请勿进行其他页面操作。</p>
				<p>上次备份时间：<span id="backUpTime">{if !$lastTime == 0}{date('Y-m-d H:i:s',$lastTime)}{/if}</span> </p>
			</div>
			<div class="row">
				<div class="col-md-12">
					<button type="button" class="btn blue " id="backUpBtn" onclick="runBackUp('')">开始备份</button>
					<span id="BackupProgess"></span>
				</div>
			</div>
		</div>
	</div>
	<!--content-wrapper /-->
</div>
<!-- container /-->
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="page-footer-inner">
		 2014 &copy; Metronic by keenthemes.
	</div>
	<div class="scroll-to-top">
		<i class="icon-arrow-up"></i>
	</div>
</div>
<!-- END FOOTER -->
{include file='public/admincp/footer.tpl'}

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/respond.min.js"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/excanvas.min.js"></script> 
<![endif]-->

<script>
$(function() {    
    Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
});
//备份
var progress=$("#BackupProgess"),
	backUpBtn=$("#backUpBtn");
function runBackUp(url){
	var url=url;
	backUpBtn.text('备份中...').removeClass("blue").addClass("disabled");
	if(!url){
		progress.html('正在备份第1卷，请勿进行其他页面操作。');
		url="/systemcp/database/ajax?op=backup";
	}
	new backUpAjax(url);
}
//备份请求
function backUpAjax(url){
	$.ajax({
		url:url,
		dataType: 'json',
    	type: 'post',
	}).done(function(data){
		if(data.errno==0){
			//成功，继续分卷
			setTimeout(function(){
				progress.html('正在备份第'+data.page+'卷，请勿进行其他页面操作。');
				new runBackUp(data.url);
			},1000)
		}else if(data.errno==1){
			//分卷结束
			progress.html('<a href="'+data.downUrl+'">备份完毕，请点击本处下载</a>');
			backUpBtn.text('开始备份').removeClass("disabled").addClass("blue");
			$("#backUpTime").text(data.time);
		}else if(data.errno==2){
			//失败
			backUpBtn.text('开始备份').removeClass("disabled").addClass("blue");
		}
	})
}
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
