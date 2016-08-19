 {include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
  <link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen"> 
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/jquery-1.8.3.min.js" charset="UTF-8"></script>
 <script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
 <script type="text/javascript" src="{$smarty.const.URL_WEB}js/public/region.js" charset="UTF-8" p="{$province_id}" c1="{$city_id}" c2="{$county_id}"  id="js_region">
 	 
 </script>
 
<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">评论列表 <small>查看和管理投票评论</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/votecp/vote">投票</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/votecp/comment?vote_id={$voteid}">评论列表</a></li>
				</ul>
			</div>
			<!--搜索栏-->
			<form action="" id="search" name="search" method="get">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr role="row">
								<th class="sorting_asc" width="150px">评论人</th>
								<th class="sorting_asc" width="150px">选手姓名</th>
								<th class="sorting_asc" width="150px">选手联系方式</th>
								<th class="sorting_asc" width="200px">日期选择</th>
								<th class="sorting_asc" width="150px">状态</th>
								<th class="sorting_asc">操作</th>
							</tr>
						</thead>
						<tbody>
							<tr>
							 <input type="hidden" name="vote_id" value="{$voteid}"> 
								<td>
									<input type="search" class="form-control" placeholder="评论人" name="member_name" aria-controls="sample_1">
								</td>
								<td>
									<input type="search" class="form-control" placeholder="选手名" name="player_name" aria-controls="sample_1">
								</td>
								<td>
									<input type="search" class="form-control" placeholder="选手联系方式" name="phone" aria-controls="sample_1">
								</td>
								<td>
									<div data-date-format="yyyy-mm-dd" class="input-group date date-picker margin-bottom-5">
										<input type="text" placeholder="开始时间" name="dateline_from" readonly="" class="form-control form-filter input-sm" value="">
										<span class="input-group-btn">
										<button type="button" class="btn btn-sm default"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
									<div data-date-format="yyyy-mm-dd" class="input-group date date-picker">
										<input type="text" placeholder="截至时间" name="dateline_to" readonly="" class="form-control form-filter input-sm" value="">
										<span class="input-group-btn">
										<button type="button" class="btn btn-sm default"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</td>
								<td>
									<select class="form-control" name="status">
										<option value>请选择</option>
										<option value="1" {if $status == 1}selected="selected"{/if}>已审核</option>
										<option value="0" {if $status === '0'}selected="selected"{/if}>未审核</option>
									</select>
								</td>
								<td>
									<div class="margin-bottom-5">
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
							<div class="caption">评论列表({$count})</div>
						</div>
						<div class="portlet-body">
							<!--工具-->
							<div class="table-toolbar">
								<div class="row">
									<div class="col-md-6">
										<a class="checkDel btn red" href="javascript:;">删除勾选项</a>
										<a class="checkVerify btn green" href="javascript:;">批量通过</a>
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
												<th width="120">评论人</th>
												<th width="120">选手姓名</th>
												<th width="120">选手联系方式</th>
												<th width="400">评论内容</th>
												<th width="150">评论时间</th>
												<th width="100">状态</th>
												<th>操作</th>
											</tr>
										</thead>
										<tbody>
										{foreach $commentList as $comment}
											<tr data-commenid="{$comment.id}" class="gradeX odd" role="row"> 
												<td><input type="checkbox" class="group-checkable checkbox">{$comment.id}</td>
												<td>{$comment.member_name}</td>
												<td>{$comment.name}</td>
												<td>{$comment.phone}</td>
												<td>{$comment.comment}</td>
												<td><p>{$comment.dateline}</p></td>
												<td>
													{if $comment.status == 0}未审核{/if}
													{if $comment.status == 1}审核通过{/if}
												</td>
												<td>
													{if $comment.status == 0}<a href="javascript:;" class="verify btn green">审核通过 <i class="glyphicon glyphicon-edit"></i></a>{/if}
													<a href="javascript:;" class="delete btn red">删除 <i class="glyphicon glyphicon-remove"></i></a>
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
//审核
$('.verify').click(function(){
	var tr = $(this).closest('tr');
	var id = $(this).closest('tr').data('commenid');
	if (confirm('确定审核通过？')){
		$.post('/votecp/comment/ajax?op=audit',{id:id},function(data){
			if (data.errno == 0){
				window.location.reload();
			}else{
				alert(data.errmsg);
			}
		})
	}
})
//勾选审核
$('.checkVerify').click(function(){
	var cBoxs=$("input.checkbox:checked");
	var arr=new Array();
	if(cBoxs.length>0){
		$.each(cBoxs,function(i,d){
			var id=$(d).closest('tr').data('commenid');
			arr[i]=id;
		})
		if (confirm('确定审核通过？')){
			$.post('/votecp/comment/ajax?op=audit',{id:arr},function(data){
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
//删除
$('.delete').click(function(){
	var tr = $(this).closest('tr');
	var id = $(this).closest('tr').data('commenid');
	if (confirm('确定删除？')){
		$.post('/votecp/comment/ajax?op=delete',{id:id},function(data){
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
			var id=$(d).closest('tr').data('commenid');
			arr[i]=id;
		})
		if (confirm('确定删除？')){
			$.post('/votecp/comment/ajax?op=delete',{id:arr},function(data){
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



{include file='public/admincp/footer.tpl'}

<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
