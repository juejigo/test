 {include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
  <link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen"> 
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/jquery-1.8.3.min.js" charset="UTF-8"></script>
 <script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
 <script type="text/javascript" src="{$smarty.const.URL_WEB}js/public/region.js" charset="UTF-8" p="{$province_id}" c1="{$city_id}" c2="{$county_id}"  id="js_region">
 	 
 </script>
 
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">
	
			<h3 class="page-title">选手列表</h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/votecp/vote">投票</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/votecp/player/list?vp_id={$vp_id}">选手列表</a></li>
				</ul>
			</div>
			<!--搜索栏-->
			<form action="" id="search" name="search" method="get">
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr role="row">
							<th class="sorting_asc" width="15%">ID</th>
							<th class="sorting_asc" width="15%">姓名</th>
						    <th class="sorting_asc" width="20%">日期选择</th>
							<th class="sorting_asc" width="10%">状态</th>
							<th class="sorting_asc" width="10%">排序</th>
							<th class="sorting_asc">操作</th>
						</tr>
					</thead>
					<tbody>
						<tr>
						     <input type="hidden" name="vp_id" value="{$vp_id}"> 
							<td>
								<input type="search" class="form-control" placeholder="ID" name="id" aria-controls="sample_1">
							</td>
							<td>
								<input type="search" class="form-control" placeholder="姓名" name="name" aria-controls="sample_1">
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
								    <option value="all">全部</option>	
									<option value="1" {if $params.status == 1}selected="selected"{/if}>已审核</option>
									<option value="0" {if $params.status === '0'}selected="selected"{/if}>未审核</option>		
									<option value="-1" {if $params.status == -1}selected="selected"{/if}>审核未通过</option>
								</select>
							</td>
							<td>
								<select class="form-control" name="order">	
									<option value="0" {if $params.order === '0'}selected="selected"{/if}>不排序</option>		
									<option value="1" {if $params.order == 1}selected="selected"{/if}>排序</option>
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
			
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box grey-cascade">
						<div class="portlet-title">
							<div class="caption">
								选手列表{$count}
							</div>
							<div class="tools">
								<a href="javascript:;" class="collapse">
								</a>
								<a href="#portlet-config" data-toggle="modal" class="config">
								</a>
								<a href="javascript:;" class="reload">
								</a>
								<a href="javascript:;" class="remove">
								</a>
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="row">
									<div class="col-md-6">
											<a class="btn green" href="/votecp/player/add?vp_id={$vp_id}">
											新增 <i class="fa fa-plus"></i>
											</a>
											<!-- <a class="btn red" href="/productcp/product/add?area={$params.area}">
											删除勾选项
											</a>
											<a class="btn blue" href="/productcp/product/add?area={$params.area}">
											批量通过
											</a> -->
									</div>								
								</div>
							</div>
							<table class="table table-striped table-bordered table-hover" id="sample_1">
							<thead>
							<tr>
								<th class="table-checkbox">
									<input type="checkbox" class="group-checkable"/>
								</th>
								<th>
									 ID
								</th>
								<th>
									 真实姓名
								</th>
								<th>
									 缩略图
								</th>
								<th>
									 票数
								</th>
								<th>
									 联系方式
								</th>
								<th>
									 报名时间
								</th>
								<th>
									 审核状态
								</th>
								<th>
									 操作
								</th>
							</tr>
							</thead>
							<tbody>
							{foreach $idList as $i => $id}
							<tr data-id="{$id.id}" class="{if $i/2 == 1}odd{/if} gradeX">
								<td>
									<input type="checkbox" class="checkboxes" value="1"/>
								</td>
								<td>
								<a href="/votecp/player/edit?id={$id.id}&vp_id={$vp_id}">
										{$id.id}	
										</a>									
								</td>
								<td>
										{$id.name}{$id.rank}
								</td>
								<td>
										<!-- <img src="{$id.image}" width="150px"> -->
										<a href="/votecp/player/edit?id={$id.id}&vp_id={$vp_id}">
										<img width="100px" height="100px" src="{thumbpath source=$id.image width=60}" />
										</a>
								</td>
								<td>
										{$id.vote_num}
								</td>
								<td>
										{$id.phone}
								</td>
								<td>
										{date('Y-m-d H:i:s',$id.join_time)}
								</td>
								<td>
										{if ($id.status==0)}<span>未审核 </span>{/if}
										{if ($id.status==1)}<span>审核通过</span>{/if}
										{if ($id.status==-1)}
										<span>审核未通过</span> <br>
									    [<span>{$id.nopass}</span>]      
										{/if}	
								</td>
								
								<td>
									<a href="/votecp/player/edit?id={$id.id}&vp_id={$vp_id}" class="btn green">编辑 <i class="glyphicon glyphicon-edit"></i></a>

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
					<!-- END EXAMPLE TABLE PORTLET-->
				</div>
			</div>
			
	</div>
</div>
<!-- END CONTENT -->

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
//删除
$('.delete').click(function(){
	var tr = $(this).closest('tr');
	var id = $(this).closest('tr').data('id');
	if (confirm('确定删除？')){
		$.get('/votecp/player/delete?id=' + id,function(json){
			if (json.flag=='success'){
				tr.remove();
				window.location.reload();
				return;
			}else{
				alert(json.msg);
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



{include file='public/admincp/footer.tpl'}

<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
