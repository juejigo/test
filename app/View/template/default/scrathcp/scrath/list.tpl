 {include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
<link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen"> 
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/jquery-1.8.3.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">刮刮卡列表 <small>查看和管理刮刮卡</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/scrathcp/scrath/list">刮刮卡活动</a></li>
				</ul>
			</div>
			<!--搜索栏-->
			<form action="" id="search" name="search" method="get">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr role="row">
								<th class="sorting_asc" width="10%">ID</th>
								<th class="sorting_asc" width="20%">刮刮卡标题</th>
								<th class="sorting_asc" width="20%">日期选择</th>
								<th class="sorting_asc" width="10%">状态</th>
								<th class="sorting_asc">操作</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<input type="search" class="form-control" placeholder="活动ID" name="scrath_id" aria-controls="sample_1" value="{$params.scrath_id}">
								</td>
								<td>
									<input type="search" class="form-control" placeholder="刮刮卡标题" name="scrath_name" aria-controls="sample_1" value="{$params.scrath_name}">
								</td>
								<td>
									<div data-date-format="yyyy-mm-dd" class="input-group date date-picker margin-bottom-5">
										<input type="text" placeholder="开始时间" name="dateline_from" readonly="" class="form-control form-filter input-sm" value="{$params.dateline_from}">
										<span class="input-group-btn">
										<button type="button" class="btn btn-sm default"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
									<div data-date-format="yyyy-mm-dd" class="input-group date date-picker">
										<input type="text" placeholder="截至时间" name="dateline_to" readonly="" class="form-control form-filter input-sm" value="{$params.dateline_to}">
										<span class="input-group-btn">
										<button type="button" class="btn btn-sm default"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</td>
								<td>
									<select class="form-control" name="status">
										<option value="">全部</option>
										<option value="1"{if $status == 1}selected="selected"{/if}>进行中</option>
										<option value="2"{if $params.status == 2}selected="selected"{/if}>已结束</option>
										<option value="0"{if $params.status === '0'}selected="selected"{/if}>已下架</option>
									</select>
								</td>
								<td>
									<div class="margin-bottom-5">
										<input type="hidden" name="area" value="0">
										<button class="btn btn-sm yellow filter-submit margin-bottom" type="submit"><i class="fa fa-search"></i> 搜索</button>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</form>
			<!--搜索栏 /-->

			<!--表格列表-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box grey-cascade">
						<!--表格标题-->
						<div class="portlet-title">
							<div class="caption">刮刮卡列表   {$count}</div>
						</div>
						<div class="portlet-body">
							<!--工具-->
							<div class="table-toolbar">
								<div class="row">
									<div class="col-md-6">
										<a class="btn green" href="/scrathcp/scrath/add">新增 <i class="fa fa-plus"></i></a>
										<a class="checkDel btn red" href="javascript:;">删除勾选项</a>
									</div>
									<div class="col-md-6">
										<div class="pull-right">
											<div class="btn-group">
												<button type="button" class="btn default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">导出 <i class="fa fa-angle-down"></i></button>
												<ul class="dropdown-menu pull-right">
													<li><a target="_blank" href="/fundscp/funds/exportorder?status=0&amp;auth=1">导出支付EXCEL表</a></li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!--表格-->
							<div class="dataTables_wrapper no-footer" id="sample_1_wrapper">
								<div class="table-scrollable">
									<table class="table table-striped table-bordered table-hover dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
										<thead>
											<tr role="row">
												<th width="80"><input type="checkbox" class="group-checkable" id="allCheck">ID</th>
												<th width="300">刮刮卡标题</th>
												<th width="350">开始时间/结束时间</th>
												<th width="100">每个用户抽奖次数</th>
												<th width="100">状态</th>
												<th width="350">操作</th>
												<th>刮卡记录</th>
											</tr>
										</thead>
										<tbody>
										{foreach $scrathList as $scrath}
											<tr data-id="{$scrath.id}" class="gradeX odd" role="row"> 
												<td><input type="checkbox" class="group-checkable checkbox">{$scrath.id}</td>
												<td>{$scrath.scrath_name}</td>
												<td>{$scrath.start_time} 至  {$scrath.end_time}</td>
												<td>{$scrath.lottery_num}次</td>
												<td>
												{if $scrath.status == 0}<span class="label label-warning">待上架</span>{/if}
												{if $scrath.status == 1}<span class="label label-success">上架中</span>{/if}
												{if $scrath.status == 2}<span class="label label-danger">过期</span>{/if}
												</td>
												<td>
													<a href="/scrathcp/scrath/edit?id={$scrath.id}" class="btn green">编辑 <i class="glyphicon glyphicon-edit"></i></a>
														{if $scrath.status == 1}
														<a href="javascript:;" class="down btn yellow">下架 <i class="glyphicon glyphicon-edit"></i></a>
														{else if $scrath.status == 0}
														<a href="javascript:;" class="up btn blue">上架 <i class="glyphicon glyphicon-edit"></i></a>
														{/if}
													<a href="javascript:;" class="delete btn red">删除 <i class="glyphicon glyphicon-remove"></i></a>		
												</td>
												<td>
													<a href="/scrathcp/card/list?scrath_id={$scrath.id}" class="btn btn-default">查看</a>
												</td>
											</tr>
										{/foreach}											
											<tr>
												<td colspan="10">{$pagebar}</td>
											</tr>
										</tbody>							
									</table>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
			<!--表格结束 /-->
		</div>
	</div>
	<!--content-wrapper /-->
</div>
<!-- container /-->
 {include file='public/admincp/footer.tpl'}
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<!-- END PAGE LEVEL SCRIPTS -->
{literal}
<script>
$(function() {    
    Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
	//添加日期选择功能 
    $(".date-picker").datepicker({ 
		rtl: Metronic.isRTL(),
        autoclose: true,
    });
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
//下架
$('.down').click(function(){
	var tr = $(this).closest('tr');
	var id = $(this).closest('tr').data('id');
	if (confirm('确定下架？')){
		$.post('/scrathcp/scrath/ajax?op=down',{id:id},function(data){
			if (data.errno == 0){
				window.location.reload();
			}else{
				alert(data.errmsg);
			}
		})
	}
})
// 上架
$('.up').click(function(){
	var tr = $(this).closest('tr');
	var id = $(this).closest('tr').data('id');
	if (confirm('确定上架？')){
		$.post('/scrathcp/scrath/ajax?op=up',{id:id},function(data){
			if (data.errno == 0){
				window.location.reload();
			}else{
				alert(data.errmsg);
			}
		})
	}
})
//删除
$('.delete').click(function(){
	var tr = $(this).closest('tr');
	var id = $(this).closest('tr').data('id');
	if (confirm('确定删除？')){
		$.post('/scrathcp/scrath/ajax?op=delete',{id:id},function(data){
			if (data.errno == 0){
				tr.remove();
				return;
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
			$.post('/scrathcp/scrath/ajax?op=delete',{id:arr},function(data){
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
