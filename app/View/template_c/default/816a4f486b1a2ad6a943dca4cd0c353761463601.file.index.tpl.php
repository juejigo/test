<?php /* Smarty version Smarty-3.1.11, created on 2016-08-12 09:07:45
         compiled from "app\View\template\default\admincp\index\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1516357ad2161eae999-76092954%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '816a4f486b1a2ad6a943dca4cd0c353761463601' => 
    array (
      0 => 'app\\View\\template\\default\\admincp\\index\\index.tpl',
      1 => 1469176716,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1516357ad2161eae999-76092954',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'orderCountToday' => 0,
    'orderCountAll' => 0,
    'memberCountToday' => 0,
    'memberCountAll' => 0,
    'summeryToday' => 0,
    'summery' => 0,
    'productTop' => 0,
    'v' => 0,
    'memberTop' => 0,
    'waitConfirmOrderTop' => 0,
    'waitReturnProductOrderTop' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_57ad216207e774_75365928',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ad216207e774_75365928')) {function content_57ad216207e774_75365928($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ('public/admincp/header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php echo $_smarty_tpl->getSubTemplate ("public/admincp/siderbar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<script src="/static/style/default/mix/metronic3.6/global/plugins/highcharts/highcharts.js" type="text/javascript"></script>
<script src="/static/style/default/mix/metronic3.6/global/plugins/highcharts/modules/exporting.js" type="text/javascript"></script>
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<h3 class="page-title">
				控制面板 <small>报告和统计</small>
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="index.html">首页</a></li>
				</ul>
			</div>
			<!-- 3个数据展示-->
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 margin-bottom-10">
					<div class="dashboard-stat blue-madison">
						<div class="visual">
							<i class="fa fa-briefcase fa-icon-medium"></i>
						</div>
						<div class="details">
							<div class="number" style="font-size:16px;">本日订单：<?php echo $_smarty_tpl->tpl_vars['orderCountToday']->value;?>
</div>
							<div class="desc">总订单：<?php echo $_smarty_tpl->tpl_vars['orderCountAll']->value;?>
</div>
						</div>
						<a class="more" href="/ordercp/order/list">查看更多 <i class="m-icon-swapright m-icon-white"></i></a>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat red-intense">
						<div class="visual">
							<i class="fa fa-briefcase fa-icon-medium"></i>
						</div>
						<div class="details">
							<div class="number" style="font-size:16px;">本日注册会员：<?php echo $_smarty_tpl->tpl_vars['memberCountToday']->value;?>
</div>
							<div class="desc">总会员：<?php echo $_smarty_tpl->tpl_vars['memberCountAll']->value;?>
</div>
						</div>
						<a class="more" href="/usercp/member/list">查看更多 <i class="m-icon-swapright m-icon-white"></i></a>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat green-haze">
						<div class="visual">
							<i class="fa fa-briefcase fa-icon-medium"></i>
						</div>
						<div class="details">
							<div class="number" style="font-size:16px;">本日交易额：<?php echo $_smarty_tpl->tpl_vars['summeryToday']->value;?>
元</div>
							<div class="desc">总交易额：<?php echo $_smarty_tpl->tpl_vars['summery']->value;?>
元</div>
						</div>
						<a class="more" href="/financecp/detail/flow">查看更多 <i class="m-icon-swapright m-icon-white"></i></a>
					</div>
				</div>
			</div>
			<!-- 3个数据展示 /-->
			<!-- 表格组-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box red-sunglo">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-thumb-tack"></i>概述</div>
						</div>
						<div class="portlet-body">
							<div class="tabbable-line">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#overview_1" data-toggle="tab" aria-expanded="true">产品 </a></li>
									<li class=""><a href="#overview_2" data-toggle="tab" aria-expanded="false">用户 </a></li>
									<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">订单 <i class="fa fa-angle-down"></i></a>
										<ul class="dropdown-menu" role="menu">
										<li class=""><a href="#overview_3" tabindex="-1" data-toggle="tab" aria-expanded="true">已付款待确认 </a></li>
										<li class=""><a href="#overview_4" tabindex="-1" data-toggle="tab" aria-expanded="false">待退货 </a></li>
									</ul>
									</li>
								</ul>
								<div class="tab-content">
									<!--产品表格-->
									<div class="tab-pane active" id="overview_1">
										<div class="table-responsive">
											<table class="table table-striped table-hover table-bordered">
												<thead>
													<tr>
														<th width="55%">产品名称</th>
														<th width="15%">价格</th>
														<th width="15%">销量</th>
														<th width="15%"></th>
													</tr>
												</thead>
												<tbody>
												<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['productTop']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
													<tr>
														<td><a target="_blank" href="/product/product/detail?id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['v']->value['product_name'];?>
</a></td>
														<td><?php echo $_smarty_tpl->tpl_vars['v']->value['price'];?>
</td>
														<td><?php echo $_smarty_tpl->tpl_vars['v']->value['sells'];?>
</td>
														<td><a href="/productcp/product/edit?id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" class="btn default btn-xs green-stripe">查看 </a></td>
													</tr>
												<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
									<!--产品表格 /-->
									<!--用户表格-->
									<div class="tab-pane" id="overview_2">
										<div class="table-responsive">
											<table class="table table-striped table-hover table-bordered">
												<thead>
													<tr>
														<th width="15%">ID</th>
														<th width="40%">账号</th>
														<th width="30%">注册时间</th>
														<th width="15%"></th>
													</tr>
												</thead>
												<tbody>
												<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['memberTop']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
													<tr>
														<td><?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
</td>
														<td><?php echo $_smarty_tpl->tpl_vars['v']->value['account'];?>
</td>
														<td><?php echo $_smarty_tpl->tpl_vars['v']->value['register_time'];?>
</td>
														<td><a href="/usercp/member/edit?id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" class="btn default btn-xs green-stripe">查看 </a></td>
													</tr>
												<?php } ?>
												
												</tbody>
											</table>
										</div>
									</div>
									<!--用户表格 /-->
									<!--已付款待确认-->
									<div class="tab-pane" id="overview_3">
										<div class="table-responsive">
											<table class="table table-striped table-hover table-bordered">
												<thead>
													<tr>
														<th width="30%">客户姓名</th>
														<th width="25%">日期</th>
														<th width="15%">金额</th>
														<th width="15%">状态</th>
														<th width="15%"></th>
													</tr>
												</thead>
												<tbody>
												<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['waitConfirmOrderTop']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
													<tr>
														<td><?php echo $_smarty_tpl->tpl_vars['v']->value['buyer_name'];?>
</td>
														<td><?php echo $_smarty_tpl->tpl_vars['v']->value['dateline'];?>
</td>
														<td><?php echo $_smarty_tpl->tpl_vars['v']->value['pay_amount'];?>
</td>
														<td><span class="label label-warning">待确认</span></td>
														<td><a href="/ordercp/order/detail?id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" class="btn default btn-xs green-stripe">查看 </a></td>
													</tr>
												<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
									<!--已付款待确认 /-->
									<!--待退款-->
									<div class="tab-pane" id="overview_4">
										<div class="table-responsive">
											<table class="table table-striped table-hover table-bordered">
												<thead>
													<tr>
														<th width="30%">客户姓名</th>
														<th width="25%">日期</th>
														<th width="15%">金额</th>
														<th width="15%">状态</th>
														<th width="15%"></th>
													</tr>
												</thead>
												<tbody>
												<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['waitReturnProductOrderTop']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
													<tr>
														<td><?php echo $_smarty_tpl->tpl_vars['v']->value['buyer_name'];?>
</td>
														<td><?php echo $_smarty_tpl->tpl_vars['v']->value['dateline'];?>
</td>
														<td><?php echo $_smarty_tpl->tpl_vars['v']->value['pay_amount'];?>
</td>
														<td><span class="label label-warning">待退款</span></td>
														<td><a href="/ordercp/order/detail?id=<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" class="btn default btn-xs green-stripe">查看 </a></td>
													</tr>
												<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
									<!--待退款 /-->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- 表格组 /-->
			<!--会员人数图表-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box blue-steel">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-thumb-tack"></i>会员注册数据统计</div>
						</div>
						<div class="portlet-body">
							<div class="clearfix" style="padding-bottom:10px;">
								<div class="btn-group" data-toggle="buttons">
									<label class="btn blue btn-sm active" onclick="userCharts('yesterday')"><input type="radio" class="toggle"> 昨日 </label>
									<label class="btn blue btn-sm" onclick="userCharts('today')"><input type="radio" class="toggle"> 今日 </label>
									<label class="btn blue btn-sm" onclick="userCharts('month')"><input type="radio" class="toggle"> 本月 </label>
								</div>
							</div>
							<div id="userChart" class="chart"></div>
							<div class="well no-margin no-border">
								<div class="row">
									<div class="col-md-3 col-sm-3 col-xs-6 text-stat" id ='dayMemberRegDiv'>
										<span class="label label-info">日注册会员人数: </span>
										<h3 id = 'dayMemberReg'></h3>
									</div>
									<div class="col-md-3 col-sm-3 col-xs-6 text-stat" id= 'monthMemberRegDiv'>
										<span class="label label-info">月注册会员人数: </span>
										<h3 id = 'monthMemberReg'></h3>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--会员人数图表 /-->
			<!--订单数图表-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box red-sunglo">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-thumb-tack"></i>订单数据统计</div>
						</div>
						<div class="portlet-body">
							<div class="clearfix" style="padding-bottom:10px;">
								<div class="btn-group" data-toggle="buttons">
									<label class="btn red btn-sm active" onclick='orderCharts("yesterday")'><input type="radio" class="toggle"> 昨日 </label>
									<label class="btn red btn-sm" onclick='orderCharts("today")'><input type="radio" class="toggle"> 今日 </label>
									<label class="btn red btn-sm" onclick='orderCharts("month")'><input type="radio" class="toggle"> 本月 </label>
								</div>
							</div>
							<div id="orderChart" class="chart"></div>
							<div class="well no-margin no-border">
								<div class="row">
									<div class="col-md-3 col-sm-3 col-xs-6 text-stat" id = 'dayOrderDiv'>
										<span class="label label-danger">日订单数: </span>
										<h3 id ='dayOrder'></h3>
									</div>
									<div class="col-md-3 col-sm-3 col-xs-6 text-stat" id = 'dayTradeDiv'>
										<span class="label label-danger">日交易额: </span>
										<h3 id = 'dayTrade'></h3>
									</div>

									<div class="col-md-3 col-sm-3 col-xs-6 text-stat" id ='monthOrderDiv'>
										<span class="label label-danger">本月订单数: </span>
										<h3 id ='monthOrder'></h3>
									</div>
									<div class="col-md-3 col-sm-3 col-xs-6 text-stat" id= 'monthTradeDiv'>
										<span class="label label-danger">本月交易额: </span>
										<h3 id = 'monthTrade'></h3>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--订单数图表 /-->
		</div>
	</div>
<!-- END CONTENT -->

<?php echo $_smarty_tpl->getSubTemplate ('public/admincp/footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>