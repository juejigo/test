 {include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
	<!-- 左侧列表 /-->
	<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">投票列表 <small>查看和管理投票活动</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/votecp/vote">投票列表</a></li>
				</ul>
			</div>
			<!--搜索栏-->
			<form action="" id="search" name="search" method="get">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr role="row">
								<th class="sorting_asc" width="10%">活动ID</th>
								<th class="sorting_asc" width="20%">活动标题</th>
								<th class="sorting_asc" width="20%">活动日期</th>
								<th class="sorting_asc" width="10%">状态</th>
								<th class="sorting_asc">操作</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<input type="search" class="form-control" placeholder="活动ID" name="voteId" aria-controls="sample_1">
								</td>
								<td>
									<input type="search" class="form-control" placeholder="活动标题" name="votename" aria-controls="sample_1">
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
									    <option value="all">请选择</option>
										<option value="1" {if $params.status == 1}selected="selected"{/if}>进行中</option>
										<!-- <option value="-1">已删除</option> -->
										<option value="0" {if $params.status === '0'}selected="selected"{/if}>未上架</option>
										<option value="2" {if $params.status == 2}selected="selected"{/if}>已结束</option>
									</select>
								</td>
								<td>
									<div class="margin-bottom-5">
										<!-- <input type="hidden" name="area" value="0"> -->
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
							<div class="caption">投票列表</div>
						</div>
						<div class="portlet-body">
							<!--工具-->
							<div class="table-toolbar">
								<div class="row">
									<div class="col-md-6">
										<a class="btn green" href="/votecp/vote/add">新增 <i class="fa fa-plus"></i></a>
										<!-- <a class="checkDel btn red" href="javascript:;">删除勾选项</a> -->
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
												<th width="170">图片</th>
												<th width="350">活动标题</th>
												<th width="300">时间</th>
												<th width="120">状态</th>
												<th width="400">操作</th>
												<th>管理</th>
											</tr>
										</thead>
										<tbody>		
											{foreach $idList as $i => $id}
							  					<tr data-id="{$id.id}" class="{if $i/2 == 1}odd{/if} gradeX">
													<td>
														<input type="checkbox" class="group-checkable checkbox" value="1"/>{$id.id}
													</td>
													<td>
														<!-- <img src="{$id.image}" width="150px"> -->
														<img width="150px" src="{thumbpath source=$id.image width=220}" />
													</td>
													<td>
														{$id.vote_name}
													</td>
													<td>	
														[报名时间]</br>
														{date('Y-m-d',$id.start_time)}   到    {date('Y-m-d',$id.end_time)}</br>		
														[投票时间]</br>
														{date('Y-m-d',$id.vstart_time)}  到    {date('Y-m-d',$id.vend_time)}
													</td>
													<td>
														{if ($id.t_status==1)}<span>报名期</span>{/if}
														{if ($id.t_status==2)}<span>投票期</span>{/if}
														{if ($id.t_status==3)}<span>过期</span>{/if}
													</td>
													<td>
														<a href="/votecp/vote/edit?id={$id.id}" class="btn green">编辑 <i class="glyphicon glyphicon-edit"></i></a>
														{if $id.status == 1}
														<a href="javascript:;" class="down btn yellow">下架 <i class="glyphicon glyphicon-edit"></i></a>
														{else if $id.status == 0}
														<a href="javascript:;" class="up btn yellow">上架 <i class="glyphicon glyphicon-edit"></i></a>
														{/if}
														<a href="javascript:;" class="delete btn red">删除 <i class="glyphicon glyphicon-remove"></i></a>
														<!-- <a href="javascript:;" class="copy btn grey-cascade">复制链接 <i class="fa fa-chain"></i></a> -->
													</td>
													<td class="text-center">
														<p><a href="/votecp/player/list?vp_id={$id.id}&status=all" class="btn btn-default">选手列表</a></p>
														<p><a href="/votecp/record/list?vr_id={$id.id}" class="btn btn-default">投票记录</a></p>
														<p><a href="/votecp/comment/list?vote_id={$id.id}" class="btn btn-default">评论记录</a></p>
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

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/respond.min.js"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/excanvas.min.js"></script> 
<![endif]-->
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
		$.post('/votecp/vote/down',{id:id},function(data){
			if (data.errno == 0){
				window.location.reload();
			}else{
				alert(data.msg);
			}
		})
	}
})
// 上架
$('.up').click(function(){
	var tr = $(this).closest('tr');
	var id = $(this).closest('tr').data('id');
	if (confirm('确定上架？')){
		$.post('/votecp/vote/up',{id:id},function(data){
			if (data.errno == 0){
				window.location.reload();
			}else{
				alert(data.msg);
			}
		})
	}
})
//删除
$('.delete').click(function(){
	var tr = $(this).closest('tr');
	var id = $(this).closest('tr').data('id');
	if (confirm('确定删除？')){
		$.get('/votecp/vote/delete?id=' + id,function(json){
			if (json.flag=='success'){
				tr.remove();
				window.location.reload();
				return;
			}else{
				alert(data.msg);
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
			$.post('/votecp/vote/delete?id=',{ids:arr},function(data){
				if (data.errno == 0){
					window.location.reload();
				}else{
					alert(data.msg);
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
