<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" />
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>| 后台管理</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>-->
<link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
<link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css"/>
 <!-- END PAGE LEVEL PLUGIN STYLES -->
<!-- BEGIN THEME STYLES -->
<!-- DOC: To use 'rounded corners' style just load 'components-rounded.css' stylesheet instead of 'components.css' in the below style tag -->
<link href="{$smarty.const.URL_MIX}metronic3.6/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- BEGIN PAGE STYLES -->
<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_CSS}lib/jqueryui/jqueryui.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_CSS}public/admincp/common.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_CSS}{$module}/{$controller}/{$action}.css" />
<!-- END PAGE STYLES -->
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
<style>
.opno{ display:block!important;opacity:0!important}
.page-header.navbar .hor-menu .navbar-nav>li .dropdown-menu .mega-menu-content{ padding:20px;overflow:hidden;width:680px}
.page-header.navbar .hor-menu .navbar-nav>li .dropdown-menu .mega-menu-content .topbar{ width:160px;float:left;padding-right:10px}
.page-header.navbar .hor-menu .navbar-nav>li .dropdown-menu .mega-menu-content .topbar-nav-item{ padding-bottom:10px;border-bottom:1px solid #5a6571;float:left}
.page-header.navbar .hor-menu .navbar-nav>li .dropdown-menu .mega-menu-content .topbar-nav-item ul{ padding:0;margin:8px 0 0 0;list-style:none}
.page-header.navbar .hor-menu .navbar-nav>li .dropdown-menu .mega-menu-content .topbar-nav-item li{ width:150px;margin-bottom:2px}
.page-header.navbar .hor-menu .navbar-nav>li .dropdown-menu .mega-menu-content .topbar-nav-item li>a{ height:28px;line-height:28px;display:block;height:100%;padding:0 10px 0 0;text-decoration:none;color:#b4bcc8}
.page-header.navbar .hor-menu .navbar-nav>li .dropdown-menu .mega-menu-content .topbar-nav-item li>a:hover{ color:#fff}
.page-header.navbar .hor-menu .navbar-nav>li .dropdown-menu .mega-menu-content .topbar-nav-item-title{ margin:3px 0;color:#fff;font-weight:600}

</style>
</head>
<!-- END HEAD -->
<body>

<body class="page-header-fixed page-quick-sidebar-over-content page-full-width">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
	<!-- BEGIN HEADER INNER -->
	<div class="page-header-inner">
		<!-- BEGIN LOGO -->
<!-- 		<div class="page-logo"> -->
<!-- 			<a href="index.html"> -->
			<!--<img src="../../assets/admin/layout/img/logo.png" alt="logo" class="logo-default"/>-->
<!-- 			</a> -->
<!-- 			<div class="menu-toggler sidebar-toggler hide"> -->
				<!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
<!-- 			</div> -->
<!-- 		</div> -->
		<!-- END LOGO -->
		
		<!-- BEGIN HORIZANTAL MENU -->
		<!-- DOC: Remove "hor-menu-light" class to have a horizontal menu with theme background instead of white background -->
		<!-- DOC: This is desktop version of the horizontal menu. The mobile version is defined(duplicated) in the responsive menu below along with sidebar menu. So the horizontal menu has 2 seperate versions -->
		<div class="hor-menu hidden-sm hidden-xs">
			<ul class="nav navbar-nav">
                <li class="mega-menu-dropdown">
					<a data-toggle="dropdown" href="javascript:;" class="dropdown-toggle hover-initialized" data-hover="megamenu-dropdown" data-close-others="true">功能与设置 <i class="fa fa-angle-down"></i></a>
					<ul class="dropdown-menu" style="min-width:720px;" id="menu">
						<li>
							<div class="mega-menu-content" id="topNav">
								<div class="topbar"></div>
								<div class="topbar"></div>
								<div class="topbar"></div>
								<div class="topbar"></div>
							</div>
						</li>
					</ul>
				</li>
			</ul>
		</div>
		<!-- END HORIZANTAL MENU -->
		
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
		</a>
		<!-- END RESPONSIVE MENU TOGGLER -->
		<!-- BEGIN TOP NAVIGATION MENU -->
		<div class="top-menu">
			<ul class="nav navbar-nav pull-right">
				<!-- BEGIN NOTIFICATION DROPDOWN -->
				<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
				<li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<i class="icon-bell"></i>
					<span class="badge badge-default"> 7 </span>
					</a>
					<ul class="dropdown-menu">
						<li class="external">
							<h3>消息提示</h3>
							<a href="extra_profile.html">查看全部</a>
						</li>
						<li>
							<ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">
								<li>
									<a href="javascript:;">
									<span class="time">一分钟以内</span>
									<span class="details">有新的订单</span>
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</li>
				<!-- END NOTIFICATION DROPDOWN -->
				
				
				<!-- BEGIN USER LOGIN DROPDOWN -->
				<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
				<li class="dropdown dropdown-user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<img alt="" class="img-circle" src="../../assets/admin/layout/img/avatar3_small.jpg"/>
					<span class="username username-hide-on-mobile">
					{$user->account} </span>
					<i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu dropdown-menu-default">
						<li>
							<a href="/admin/index/logout">
							<i class="icon-key"></i> 退出登录 </a>
						</li>
					</ul>
				</li>
				<!-- END USER LOGIN DROPDOWN -->
				<!-- BEGIN QUICK SIDEBAR TOGGLER -->
				<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
				<li class="dropdown dropdown-quick-sidebar-toggler">
					<a href="javascript:;" class="dropdown-toggle">
					<i class="icon-logout"></i>
					</a>
				</li>
				<!-- END QUICK SIDEBAR TOGGLER -->
			</ul>
		</div>
		<!-- END TOP NAVIGATION MENU -->
	</div>
	<!-- END HEADER INNER -->
</div>
<div class="nav_fixed_two tabbable-line">
	<ul class="nav nav-tabs ">
	{foreach $submenus as $controName => $controValue}
		<li class="{if $openSub == $controName}active{/if}">
		{foreach $controValue as $v}
			<a href="/{$v['module']}/{$v['controller']}/index">{$controName}</a>
		{break}
		{/foreach}
		</li>
	{/foreach}
	</ul>
	
	<div class="tab-content">
	<div class="tab-pane active"style="min-height:auto;padding-top:0px;">
	{foreach $submenus as $controName => $controValue}
			{foreach $controValue as $actionName => $actionValue}
			{if $openSub == $controName}
				<a href="{$actionValue['url']}" class="{if $currSub == $actionName}btn blue btn-sm{else if}btn btn-info btn-sm{/if}">{$actionName}</a>		
			{/if}
			{/foreach}
	
	{/foreach}
	</div>
 	</div>
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->

<div class="page-container">