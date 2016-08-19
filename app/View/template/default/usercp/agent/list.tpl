{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
	<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">代理商管理 <small>查看和管理代理商</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="#">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/usercp/agent/list">代理商</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/usercp/agent/list">代理商管理</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/usercp/agent/list">代理商列表</a></li>
				</ul>
			</div>
			<!--表格列表-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet-body">
						<!--工具-->
						<div class="table-toolbar">
							<div class="row">
								<div class="col-md-6">
									<a class="btn green" href="/usercp/agent/add">新增 <i class="fa fa-plus"></i></a>
									<a class="checkDel btn red" href="javascript:;">删除勾选项</a>
								</div>
							</div>
						</div>
						<!--表格-->
						<div class="dataTables_wrapper no-footer">
							<div class="table-scrollable">
								<table class="table table-striped table-bordered table-hover dataTable no-footer" role="grid" aria-describedby="sample_1_info">
									<thead>
										<tr role="row">
											<th width="80"><input type="checkbox" class="group-checkable" id="allCheck">ID</th>
											<th width="200">代理商</th>
											<th width="150">代理城市</th>
											<th width="250">公司名称</th>
											<th width="150">联系人</th>
											<th width="150">联系手机</th>
											<th width="150">联系座机</th>
											<th>操作</th>
										</tr>
									</thead>
									<tbody>
									{foreach $agentList as $v}
										<tr data-id="{$v['id']}">
											<td><input type="checkbox" class="group-checkable checkbox">{$v['id']}</td>
											<td>{$v['agent_name']}</td>
											<td>{$v['region_name']}</td>
											<td>{$v['company_name']}</td>
											<td>{$v['linkman']}</td>
											<td>{$v['linkman_mobile']}</td>
											<td>{$v['telephone']}</td>
											<td>
												<a href="/usercp/agent/edit?id={$v['id']}" class="btn green">编辑 <i class="glyphicon glyphicon-edit"></i></a>
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
$('.delete').click(function(){
	var tr = $(this).closest('tr');
	var id = $(this).closest('tr').data('id');
	if (confirm('确定删除？')){
		$.post('/usercp/agent/ajax?op=delete',{id:id},function(data){
			if (data.errno == 0){
				tr.remove();
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
			$.post('/usercp/agent/ajax?op=delete',{id:arr},function(data){
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
