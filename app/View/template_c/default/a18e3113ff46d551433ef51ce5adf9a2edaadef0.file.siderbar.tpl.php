<?php /* Smarty version Smarty-3.1.11, created on 2016-08-12 09:07:46
         compiled from "app\View\template\default\public\admincp\siderbar.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2449757ad2162158266-78953994%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a18e3113ff46d551433ef51ce5adf9a2edaadef0' => 
    array (
      0 => 'app\\View\\template\\default\\public\\admincp\\siderbar.tpl',
      1 => 1469495105,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2449757ad2162158266-78953994',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'submenus' => 0,
    'openSub' => 0,
    'controName' => 0,
    'controValue' => 0,
    'currSub' => 0,
    'actionName' => 0,
    'actionValue' => 0,
    'controller' => 0,
    'action' => 0,
    'module' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_57ad2162218f08_76082736',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ad2162218f08_76082736')) {function content_57ad2162218f08_76082736($_smarty_tpl) {?>
<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
	<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
	<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
	<div class="page-sidebar navbar-collapse collapse">
		<!-- BEGIN SIDEBAR MENU -->
		<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
		<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
		<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
		<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		<ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
			<!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
			<li class="sidebar-toggler-wrapper">
				<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				<div class="sidebar-toggler">
				</div>
				<!-- END SIDEBAR TOGGLER BUTTON -->
			</li>
			<!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
			<li class="sidebar-search-wrapper">
				<!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
				<!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
				<!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->
				<form class="sidebar-search " action="extra_search.html" method="POST">
					<a href="javascript:;" class="remove">
					<i class="icon-close"></i>
					</a>
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Search...">
						<span class="input-group-btn">
						<a href="javascript:;" class="btn submit"><i class="icon-magnifier"></i></a>
						</span>
					</div>
				</form>
				<!-- END RESPONSIVE QUICK SEARCH FORM -->
			</li>
		 <?php  $_smarty_tpl->tpl_vars['controValue'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['controValue']->_loop = false;
 $_smarty_tpl->tpl_vars['controName'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['submenus']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['controValue']->key => $_smarty_tpl->tpl_vars['controValue']->value){
$_smarty_tpl->tpl_vars['controValue']->_loop = true;
 $_smarty_tpl->tpl_vars['controName']->value = $_smarty_tpl->tpl_vars['controValue']->key;
?>
			 <li class="<?php if ($_smarty_tpl->tpl_vars['openSub']->value==$_smarty_tpl->tpl_vars['controName']->value){?>active open<?php }?>">
				<a href="javascript:;">
				<i class="<?php echo $_smarty_tpl->tpl_vars['controValue']->value['icon'];?>
"></i>
				<span class="title"><?php echo $_smarty_tpl->tpl_vars['controName']->value;?>
</span>
				<span class="arrow">
				</a>
 				<?php $_smarty_tpl->tpl_vars['controValue'] = new Smarty_variable(array_splice($_smarty_tpl->tpl_vars['controValue']->value,1), null, 0);?>
				<ul class="sub-menu" id="sub">
					<?php  $_smarty_tpl->tpl_vars['actionValue'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['actionValue']->_loop = false;
 $_smarty_tpl->tpl_vars['actionName'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['controValue']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['actionValue']->key => $_smarty_tpl->tpl_vars['actionValue']->value){
$_smarty_tpl->tpl_vars['actionValue']->_loop = true;
 $_smarty_tpl->tpl_vars['actionName']->value = $_smarty_tpl->tpl_vars['actionValue']->key;
?>
						<li class="<?php if ($_smarty_tpl->tpl_vars['currSub']->value==$_smarty_tpl->tpl_vars['actionName']->value){?>active<?php }?>">
							<a href="<?php echo $_smarty_tpl->tpl_vars['actionValue']->value['url'];?>
"><?php echo $_smarty_tpl->tpl_vars['actionName']->value;?>
</a>
						</li>	
					<?php } ?>
				</ul>
			</li>
		 <?php } ?>
		 
			
		</ul>
		<!-- END SIDEBAR MENU -->
	</div>
</div>


<!-- END SIDEBAR -->




<!-- 
					<?php if ($_smarty_tpl->tpl_vars['controller']->value!=$_smarty_tpl->tpl_vars['actionValue']->value['controller']&&$_smarty_tpl->tpl_vars['action']->value!=$_smarty_tpl->tpl_vars['actionValue']->value['action']&&$_smarty_tpl->tpl_vars['module']->value=='votecp'&&$_smarty_tpl->tpl_vars['controller']->value=='player'){?>
						<li class="active open">
						<a href="javascript:;">
							<i class="icon-basket"></i>
							<span class="title">投票</span>
							<?php if ($_smarty_tpl->tpl_vars['controller']->value=='vote'){?><span class="selected"></span><?php }?>
							<span class="arrow">
							</a>
							<ul class="sub-menu">
									<li class="<?php if ($_smarty_tpl->tpl_vars['controller']->value=='vote'||$_smarty_tpl->tpl_vars['controller']->value=='player'||$_smarty_tpl->tpl_vars['controller']->value=='record'||$_smarty_tpl->tpl_vars['controller']->value=='comment'){?>active<?php }?>">
										<a href="/votecp/vote/list">
										投票列表 </a>
									</li>
							</ul>
						</li>
						<li class="<?php if ($_smarty_tpl->tpl_vars['controller']->value=='scrath'||$_smarty_tpl->tpl_vars['controller']->value=='card'){?>active open<?php }?>">
							<a href="javascript:;">
							<i class="icon-basket"></i>
							<span class="title">刮刮卡</span>
							<?php if ($_smarty_tpl->tpl_vars['controller']->value=='scrath'||$_smarty_tpl->tpl_vars['controller']->value=='card'){?><span class="selected"></span><?php }?>
							<span class="arrow">
							</a>
							<ul class="sub-menu">
									<li class="<?php if ($_smarty_tpl->tpl_vars['controller']->value=='scrath'||$_smarty_tpl->tpl_vars['controller']->value=='card'){?>active<?php }?>">
										<a href="/scrathcp/scrath/list">
										刮刮卡列表 </a>
									</li>
							</ul>
						</li>					
					
					<?php }?> --><?php }} ?>