 {include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
 <link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen"> 
 <script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/jquery-1.8.3.min.js" charset="UTF-8"></script>
 <script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
 <script type="text/javascript" src="{$smarty.const.URL_WEB}js/public/region.js" charset="UTF-8" p="{$province_id}" c1="{$city_id}" c2="{$county_id}"  id="js_region">	 </script>
	<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">物流设置 <small>设置不同地区的物流费用</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/systemcp/setting/config">设置</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/systemcp/setting/config">系统设置</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/systemcp/setting/shippingtpl">物流设置</a></li>
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
					<form action="/systemcp/setting/shippingtpl" class="form-horizontal form-row-seperated" method="post">
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-edit"></i>内容设置
								</div>
								<div class="pull-right">
									<button type="reset" class="btn default"><i class="fa fa-refresh"></i> 重置</button>
									<button type="submit" class="btn green"><i class="fa fa-check-circle"></i> 保存</button>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<tr role="row">
											<th class="sorting_asc" width="50%">可配送区域</th>
											<th class="sorting_asc" width="50%">运费(元)</th>
											<th></th>
										</tr>
									</thead>
									<tbody id="tbody">
									{foreach $shipping as $ship}
											<tr data-id="'+i+'" class="gradeX odd" role="row">
											<td><select name="province_id[]" class="form-control"><option value="">请选择</option>
											{foreach $regions as $region}
											<option value="{$region.id}" {if $ship.province_id == $region.id}selected="selected"{/if}>{$region.region_name}</option>
											{/foreach}
											</select></td>
											<td><input type="text" class="form-control" name="fee[]" value="{$ship.fee}">
											<input type="hidden" name="status[]" value="1"></td>
											<td><a href="javascript:;" class="del_tr btn red">删除</a></td>
											</tr>
									{/foreach}
									</tbody>
								</table>
							</div>
							<a href="javascript:;" class="btn btn-default" id="addTr"><i class="fa fa-plus"></i> 添加区域</a>
						</div>
					</form>
					
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

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/respond.min.js"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/excanvas.min.js"></script> 
<![endif]-->
{include file='public/admincp/footer.tpl'}
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
	str+='<td><select name="province_id[]" class="form-control"><option value="">请选择</option>';
	{foreach $regions as $region}
	str+='<option value="{$region.id}">{$region.region_name}</option>';
	{/foreach}
	str+='</select></td>';
	str+='<td><input type="text" class="form-control" name="fee[] value=""><input type="hidden" name="status[]" value=""></td>';
	str+='<td><a href="javascript:;" class="del_tr btn red">删除</a></td>';
	str+='</tr>';
	tbody.append(str);
})
//删除tr
$(document).on("click",'.del_tr',function(){
	var tr = $(this).closest('tr');
	if (confirm('确定删除？')){
		if(tr.find('input[name^=status]').val() == '')
		{
			tr.remove();
		}else
		{
 			tr.find('input[name^=status]').val(-1);
 			tr.css("display","none");
		} 
	}
})
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
