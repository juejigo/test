{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">编辑等级 <small>编辑等级</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/usercp/member/list">会员</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/usercp/member/list">会员管理</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/usercp/group/list">等级管理</a></li>
				</ul>
			</div>
			<!--表单-->
			<div class="row">
				<div class="col-md-12">
					<form action="" class="form-horizontal form-row-seperated" method="post">
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-edit"></i>内容设置
								</div>
								<div class="pull-right">
									<a href="/usercp/group/list" class="btn default"><i class="fa fa-angle-left"></i> 返回</a>
									<button type="reset" class="btn default"><i class="fa fa-refresh"></i> 重置</button>
									<button type="submit" class="btn green"><i class="fa fa-check-circle"></i> 保存</button>
								</div>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="tab-content">
								<div class="form-body">
									<div class="form-group">
										<label class="control-label col-md-2">等级名称 </label>
										<div class="col-md-3"><input type="text" class="form-control" name="name" placeholder="不超过20个字" value="{$editList[0]['group_name']}"></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">所属角色 </label>
										<div class="col-md-3">
											<select name="role" class="form-control" required="">
												<option {if $editList[0].role == ''}selected="selected"{/if} value="">请选择</option>												
											{foreach $role as $v}
												<option {if $editList[0].role == $v['role']}selected="selected"{/if} value="{$v['role']}">{$v['role_name']}</option>
											{/foreach}
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">升级条件 </label>
										<div class="col-md-3">
											<div class="input-group">
												<span class="input-group-addon">积分</span>
												<input type="text" class="form-control" name="point" value="{$editList[0]['condition_point']}">
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2"> </label>
										<div class="col-md-3">
											<div class="input-group">
												<span class="input-group-addon">消费</span>
												<input type="text" class="form-control" name="consumption" value="{$editList[0]['condition_consumption']}">
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">分红设置 </label>
										<div class="col-md-3">
											<div class="input-group">
												<input type="text" class="form-control" name="ratio" value="{$editList[0]['ratio']}">						
											</div>
											<span class="help-block">请以小数形式填写， 如 0.51 （即 51%）</span>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">奖项设置 </label>
										<div class="col-md-6">
											<div class="dataTables_wrapper no-footer" id="sample_1_wrapper">
												<div class="table-scrollable">
													<table class="table table-bordered table-hover dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
														<thead>
															<tr role="row">
																<th width="50%">月营业额(元)</th>
																<th width="50%">奖励(元)</th>
																<th></th>
															</tr>
															{foreach $editList as $k => $v}
																{foreach $v['setting'] as $setname => $s}
																<tr>
																	<td><input type="text" class="form-control" name="dbturnover[{$setname}]"	value="{$s['turnover']}"	></td>
																	<td><input type="text" class="form-control" name="dbreward[{$setname}]"	value="{$s['reward']}"	></td>	
																	<td><a href="javascript:;" class="del_tr btn red">删除</a></td>																						
																</tr>
																{/foreach}																
															{/foreach}
														</thead>
														<tbody id="tbody">
														</tbody>
													</table>
												</div>
											</div>
											<a href="javascript:;" class="btn btn-default" id="addTr"><i class="fa fa-plus"></i> 添加奖项</a>
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

<!-- container /-->
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="page-footer-inner"> 2014 &copy; Metronic by keenthemes.</div>
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
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{$smarty.const.URL_MIX}metronic3.6/global/scripts/metronic.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/admin/layout/scripts/demo.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
$(function() {    
    Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
});

//增加TR
var tbody=$("#tbody");
var i=0;
$("#addTr").click(function(){
	tbody.find("tr").each(function(index,v){
		var trI=$(v).data("id");
		if(i<trI) i=trI;
	})
	i++;
	var str='';
		str+='<tr data-id="'+i+'" class="gradeX odd" role="row">';
		str+='<td><input type="text" class="form-control" name="turnover['+i+']"></td>';
		str+='<td><input type="text" class="form-control" name="reward['+i+']"></td>';
		str+='<td><a href="javascript:;" class="del_tr btn red">删除</a></td>';
		str+='</tr>';
		tbody.append(str);
	
})
//删除tr
$(document).on("click",'.del_tr',function(){
	var tr = $(this).closest('tr');
	if (confirm('确定删除？')){
		tr.remove();
	}
})
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>


<!-- 	for(var j=1;i<count($editList[0]['setting']);j++)
	{
		str+='<tr data-id="'+i+'" class="gradeX odd" role="row">';
		str+='<td><input type="text" class="form-control" name="turnover['+i+']" value ="$editListResult[0]['setting'][j]['turnover']"></td>';
		str+='<td><input type="text" class="form-control" name="reward['+i+'] value ="$editListResult[0]['setting'][j]['reward']"></td>';
		str+='<td><a href="javascript:;" class="del_tr btn red">删除</a></td>';
		str+='</tr>';
		tbody.append(str);
	} -->
