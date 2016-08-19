<?php /* Smarty version Smarty-3.1.11, created on 2016-08-12 10:27:16
         compiled from "app\View\template\default\pushcp\push\jpush.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1802257ad216751e075-65528002%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6a9a8753b3d2647ab90fd585884cd2e07a32d77d' => 
    array (
      0 => 'app\\View\\template\\default\\pushcp\\push\\jpush.tpl',
      1 => 1470968833,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1802257ad216751e075-65528002',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_57ad216759ddd1_17401302',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ad216759ddd1_17401302')) {function content_57ad216759ddd1_17401302($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ('public/admincp/header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php echo $_smarty_tpl->getSubTemplate ("public/admincp/siderbar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<link href="<?php echo @URL_MIX;?>
metronic3.6/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo @URL_MIX;?>
metronic3.6/global/plugins/icheck/skins/all.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="<?php echo @URL_MIX;?>
metronic3.6/global/plugins/select2/select2.css">
<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">推送 </h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="#">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="#">设置</a><i class="fa fa-angle-right"></i></li>
					<li><a href="#">推送</a></li>
				</ul>
			</div>
      <!-- 错误提醒 -->
      <div class="error_msg"></div>
      <!-- 错误提醒  /-->
      <!--表单-->
			<div class="row">
				<div class="col-md-12">
					<form action="/pushcp/push/ajax?op=jpush" class="form-horizontal form-row-seperated" method="post" id="jpush">
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-edit"></i>内容设置
								</div>
								<div class="pull-right">
									<a href="/admincp" class="btn default"><i class="fa fa-angle-left"></i> 返回</a>
									<button type="submit" class="btn green"><i class="fa fa-check-circle"></i> 推送</button>
								</div>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="tab-content">
								<div class="form-body">
                  <div class="form-group">
										<label class="control-label col-md-2">标题 </label>
										<div class="col-md-6">
                      <input type="text" class="form-control" name="title" placeholder="推送标题">
                    </div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">内容 </label>
										<div class="col-md-6">
                      <textarea name="content" cols="30" rows="10" class="form-control" placeholder="推送内容"></textarea>
                    </div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">平台 </label>
										<div class="col-md-6">
                      <div class="input-group">
                        <div class="icheck-inline">
                          <label><input type="checkbox" class="icheck" data-checkbox="icheckbox_square-blue" value="1" name="android"> <i class="fa fa-android"></i> Android平台</label>
                          <label><input type="checkbox" class="icheck" data-checkbox="icheckbox_square-blue" value="1" name="ios"> <i class="fa fa-apple"></i> Ios平台</label>
                        </div>
                      </div>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">目标人群 </label>
										<div class="col-md-6">
                      <select name="target" class="form-control" id="target">
												<option value="0">请选择</option>
												<option value="all">所有人</option>
                        <option value="one">用户ID</option>
											</select>
										</div>
									</div>
                  <div class="form-group">
										<label class="control-label col-md-2">选择类型 </label>
										<div class="col-md-6">
                      <select name="type" class="form-control" id="type">
												<option value="0">文本</option>
												<option value="product">产品</option>
                      							<!-- <option value="order">订单</option> -->
											</select>
										</div>
									</div>
                  <div class="form-group">
										<label class="control-label col-md-2">时间选择 </label>
										<div class="col-md-6">
                      <select name="send_type" class="form-control" id="sendType">
												<option value="0">请选择</option>
												<option value="timer">定时发送</option>
                        <option value="now">立即发送</option>
											</select>
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

	<?php echo $_smarty_tpl->getSubTemplate ('public/admincp/footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>