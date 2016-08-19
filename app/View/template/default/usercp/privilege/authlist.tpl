{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
	<link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/icheck/skins/all.css" rel="stylesheet" type="text/css"/>
	
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">权限列表 <small>查看和修改用户权限</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="#">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="#">权限</a><i class="fa fa-angle-right"></i></li>
					<li><a href="#">权限列表</a></li>
				</ul>
			</div>
			<div class="row">
				<div class="col-md-12">
					<form action="" class="form-horizontal form-row-seperated" method="post">
						<div class="portlet">
							<div class="portlet-title">
								<input type="hidden" id="userId" value="{$userId}">
								<div class="caption">
									<i class="fa fa-edit"></i>权限设置
								</div>
								<div class="pull-right">
									<a href="/usercp/member/list" class="btn default"><i class="fa fa-angle-left"></i> 返回</a>
								</div>
							</div>
						</div>
						
						<div class="portlet-body form">
							<div class="tab-content">
								<div class="form-body">
								{foreach $authlist as $p}
									{foreach $p['children'] as $k => $v}
									<div class="form-group">
										<label><h4>{$v['value']}</h4></label>
										<div class="input-group">
											<div class="icheck-inline">
											{foreach $v['children'] as $ck => $cv}
												<label><input type="checkbox" class="icheck" {if !empty($userPri) && in_array($cv['id'],$userPri)}checked{/if} data-checkbox="icheckbox_square-blue" value="{$cv['id']}">{$cv['value']}</label>
											{/foreach}
											</div>	
										</div>
									</div>
									{/foreach}
								{/foreach}
								</div>
							</div>
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
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/jstree/dist/jstree.min.js"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/icheck/icheck.min.js"></script>

<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{$smarty.const.URL_MIX}metronic3.6/global/scripts/metronic.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/admin/layout/scripts/demo.js" type="text/javascript"></script>
<script src="{$smarty.const.URL_JS}usercp/privilege/authlist.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script>
$(function() {    
    Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
});

</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>