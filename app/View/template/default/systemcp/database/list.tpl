 {include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
 <link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen"> 
 <script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/jquery-1.8.3.min.js" charset="UTF-8"></script>
 <script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
 <script type="text/javascript" src="{$smarty.const.URL_WEB}js/public/region.js" charset="UTF-8" p="{$province_id}" c1="{$city_id}" c2="{$county_id}"  id="js_region">	 </script>
 
	<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">数据下载 <small>以往备份的数据库下载</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/systemcp/setting/config">设置</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/systemcp/database">数据管理</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/systemcp/database/list">数据下载</a></li>
				</ul>
			</div>
			<!--注意说明-->
			<div class="note note-success">通过本界面对以往备份的数据库进行下载，请注意在自行恢复后，所有数据库信息包括管理员用户名密码都会恢复成备份时的状态。</div>
			<!--表格-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet-body">
						<div class="table-toolbar">
							<div class="row">
								<div class="col-md-6">
									<a class="checkDel btn red" href="javascript:;">删除勾选项</a>
								</div>
							</div>
						</div>
						<div class="dataTables_wrapper no-footer" id="sample_1_wrapper">
							<div class="table-scrollable">
								<table class="table table-striped table-bordered table-hover dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
									<thead>
										<tr role="row">
											<th width="80"><input type="checkbox" class="group-checkable" id="allCheck">ID</th>
											<th width="300">备份文件名</th>
											<th width="150">大小(字节)</th>
											<th width="200">备份时间</th>
											<th>操作</th>
										</tr>
									</thead>
									<tbody>
									{foreach $file as $key => $f}
										<tr data-id="{$f.filename}" class="gradeX odd" role="row"> 
											<td><input type="checkbox" class="group-checkable checkbox">{$key-1}</td>
											<td>{$f.filename}</td>
											<td>{$f.filesize}</td>
											<td>{$f.filetime}</td>
											<td>
												<a href="{$f.url}" class="btn green">下载&nbsp;<i class="fa fa-cloud-download"></i></a>
												<a href="javascript:;" class="del btn red">删除&nbsp;<i class="fa fa-trash-o"></i></a>
											</td>
										</tr>
									{/foreach}
									</tbody>
								</table>
							</div>
						</div>
						<!--分页-->

						<!--分页 /-->
					</div>
				</div>
			</div>
			<!--表格 /-->
			
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
<!-- END PAGE LEVEL SCRIPTS -->
{literal}
<script>
$(function() {    
    Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
});
//勾选
$("#allCheck").click(function(){
	var _this=$(this),
		cb=$(".checkbox");
	if(_this.attr("checked") == "checked"){
		cb.attr("checked","checked");
		cb.parent().addClass("checked");
		cb.parent().parent().addClass("checked");
	}else{
		cb.removeAttr("checked","checked");
		cb.parent().removeClass("checked");
		cb.parent().parent().removeClass("checked");
	}
})
//删除
$('.del').click(function(){
	var tr = $(this).closest('tr');
	var id = $(this).closest('tr').data('id');
	if (confirm('确定删除？')){
		$.post('/systemcp/database/ajax?op=delete',{filename:id},function(data){
			if (data.errno == 0){
				tr.remove();

			}else{
				alert(data.errmsg);
			}
		})
	}
})
//勾选删除
$(".checkDel").click(function(){
	var cBoxs=$("input.checkbox:checked");
	var arr=new Array();
	if(cBoxs.length>0){
		$.each(cBoxs,function(i,d){
			var id=$(d).closest('tr').data('id');
			arr[i]=id;
		})
		if (confirm('确定删除？')){
			$.post('/systemcp/database/ajax?op=delete',{filename:arr},function(data){
				if (data.errno == 0){
					window.location.reload();
				}else{
					alert(data.errmsg);
				}
			})
		}
	}else{
		alert("至少勾选一项");
	}
})
</script>
{/literal}
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
