<?php /* Smarty version Smarty-3.1.11, created on 2016-08-11 14:15:33
         compiled from "app\View\template\default\one\phase\detail.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1550257ac1805957ec2-97134801%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8550da9df028cdf6d6957de94210f2665172c388' => 
    array (
      0 => 'app\\View\\template\\default\\one\\phase\\detail.tpl',
      1 => 1470645270,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1550257ac1805957ec2-97134801',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'image' => 0,
    'img' => 0,
    'phaseInfo' => 0,
    'params' => 0,
    'prizeInfo' => 0,
    'lastPhase' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_57ac1805a39295_59261368',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ac1805a39295_59261368')) {function content_57ac1805a39295_59261368($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="renderer" content="webkit">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<title>商品详情</title>
	<link rel="stylesheet" href="<?php echo @URL_CSS;?>
one/phase/amazeui.css">
	<link rel="stylesheet" href="<?php echo @URL_CSS;?>
one/phase/fn.mobile.css">
	<link rel="stylesheet" href="<?php echo @URL_CSS;?>
one/phase/product.css">
</head>
<body class="mhome am-with-fixed-navbar">
	
	<!--轮播图-->
	<div class="am-slider am-slider-default">
		<a class="mui-icon-back mui-icon prod_go_back" href="javascript:;"></a>
	  <ul class="am-slides">
	  	<?php  $_smarty_tpl->tpl_vars['img'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['img']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['image']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['img']->key => $_smarty_tpl->tpl_vars['img']->value){
$_smarty_tpl->tpl_vars['img']->_loop = true;
?>
	    <li><img src="<?php echo $_smarty_tpl->tpl_vars['img']->value;?>
" /></li>
	  	<?php } ?>
	  </ul>
	</div>
	<!--轮播图 /-->
	<div class="prod_title">
		<?php if ($_smarty_tpl->tpl_vars['phaseInfo']->value['status']==1){?><span class="am-badge am-badge-danger am-radius">进行中</span>
        <?php }elseif($_smarty_tpl->tpl_vars['phaseInfo']->value['status']==2){?><span class="am-badge am-badge-danger am-radius">倒计时</span>
        <?php }elseif($_smarty_tpl->tpl_vars['phaseInfo']->value['status']==3){?><span class="am-badge am-badge-success am-radius">已揭晓</span>
        <?php }?>
        (第<?php echo $_smarty_tpl->tpl_vars['phaseInfo']->value['no'];?>
期)<?php echo $_smarty_tpl->tpl_vars['phaseInfo']->value['product_name'];?>

	</div>
	<?php if ($_smarty_tpl->tpl_vars['phaseInfo']->value['status']==1){?>
	<div class="prod_buy_type m_b_5">
		<div class="title">
			<span>方式1</span>
		</div>
		<div class="info_box flex-wrap">
			<div class="info flex-con">
				<div class="am-text-sm">全价购买</div>
				<div class="am-text-xs am-link-muted">无需等待，直接获得商品！</div>
				<div class="am-text-default am-text-danger">￥<?php echo $_smarty_tpl->tpl_vars['phaseInfo']->value['product_price'];?>
</div>
			</div>
			<div class="btn" style="padding-top: 15px;">
				<a class="am-btn am-btn-danger am-radius" href="/product/product/detail?id=<?php echo $_smarty_tpl->tpl_vars['phaseInfo']->value['product_id'];?>
">全价购买</a>
			</div>
		</div>
	</div>
	<div class="prod_buy_type ">
		<div class="title">
			<span>方式1</span>
		</div>
		<div class="info_box flex-wrap">
			<div class="info flex-con">
				<div class="am-text-sm">一元夺宝</div>
				<div class="am-text-xs am-link-muted">只需要<?php echo $_smarty_tpl->tpl_vars['phaseInfo']->value['price'];?>
元就有机会获得商品！</div>
				<div class="am-text-xs am-link-muted">期号：<?php echo $_smarty_tpl->tpl_vars['phaseInfo']->value['no'];?>
</div>
				<div class="am-progress am-progress-striped am-progress-xs am-active">
				  <div class="am-progress-bar am-progress-bar-danger"  style="width: <?php echo $_smarty_tpl->tpl_vars['phaseInfo']->value['now_num']/$_smarty_tpl->tpl_vars['phaseInfo']->value['need_num']*100;?>
%"></div>
				</div>
				<div class="am-text-xs am-link-muted">总需<?php echo $_smarty_tpl->tpl_vars['phaseInfo']->value['need_num'];?>
人次<span class="am-fr">剩余<span class="am-text-primary"><?php echo $_smarty_tpl->tpl_vars['phaseInfo']->value['need_num']-$_smarty_tpl->tpl_vars['phaseInfo']->value['now_num'];?>
</span></span></div>
			</div>
			<div class="btn" style="padding-top: 15px;">
				<a class="am-btn am-btn-danger am-radius" href="/oneuc/order/pay?id=<?php echo $_smarty_tpl->tpl_vars['params']->value['id'];?>
">立即夺宝</a>
			</div>
		</div>
	</div>
	<?php }elseif($_smarty_tpl->tpl_vars['phaseInfo']->value['status']==2){?>
	<!--揭晓倒计时-->
	<div class="countdown_box">
		揭晓倒计时：<span class="countdown" data-time="<?php echo $_smarty_tpl->tpl_vars['phaseInfo']->value['clock'];?>
">计算中...</span>
		<a href="/one/phase/calculte?id=<?php echo $_smarty_tpl->tpl_vars['params']->value['id'];?>
">查看计算详情</a>
	</div>
	<!--揭晓倒计时-->
	<?php }elseif($_smarty_tpl->tpl_vars['phaseInfo']->value['status']==3){?>
	<!--已揭晓获奖者-->
	<div class="winner_box bd_t">
		<div class="bg">
			<div class="jl"></div>
			<div class="info flex-wrap">
				<img src="<?php echo $_smarty_tpl->tpl_vars['prizeInfo']->value['avatar'];?>
">
				<div class="flex-con">
					<p class="am-text-sm">获奖者：<?php echo $_smarty_tpl->tpl_vars['prizeInfo']->value['member_name'];?>
</p>
					<p>ip地址：<?php echo $_smarty_tpl->tpl_vars['prizeInfo']->value['register_ip'];?>
</p>
					<p>本期参与：<span class="zcolor"><?php echo $_smarty_tpl->tpl_vars['prizeInfo']->value['num'];?>
</span>人次</p>
					<p>揭晓时间：<?php echo date("Y-m-d H:i:s",$_smarty_tpl->tpl_vars['prizeInfo']->value['lottery_time']);?>
</p>
				</div>
			</div>
			<div class="luck_box">
				幸运号码：<?php echo $_smarty_tpl->tpl_vars['prizeInfo']->value['lucky_num'];?>

				<a href="/one/phase/calculte?id=<?php echo $_smarty_tpl->tpl_vars['params']->value['id'];?>
">查看计算详情</a>
			</div>
		</div>
	</div>
	<!--已揭晓获奖者-->
	<?php }?>
	<ul class="aui-list-view">
		<li class="aui-list-view-cell bd_t"><a href="/product/product/detail?id=<?php echo $_smarty_tpl->tpl_vars['phaseInfo']->value['product_id'];?>
" class="aui-arrow-right"><img src="<?php echo @URL_IMG;?>
/one/twxq.png" >图文详情<span class="aui-right-name">建议在WiFi下查看</span></a></li>
		<li class="aui-list-view-cell bd_t"><a href="/one/phase/winrecord?id=<?php echo $_smarty_tpl->tpl_vars['phaseInfo']->value['product_id'];?>
" class="aui-arrow-right"><img src="<?php echo @URL_IMG;?>
/one/wqjx.png" >往期揭晓</a></li>
		<li class="aui-list-view-cell bd_t bd_b"><a href="/one/phase/joinrecord?id=<?php echo $_smarty_tpl->tpl_vars['phaseInfo']->value['id'];?>
" class="aui-arrow-right"><img src="<?php echo @URL_IMG;?>
/one/cyjl.png" >所有参与记录</a></li>
	</ul>

	<?php if (!empty($_smarty_tpl->tpl_vars['lastPhase']->value)){?>
	<!--下一期-->
	<div class="next_box bd_t">
		第<?php echo $_smarty_tpl->tpl_vars['lastPhase']->value['no'];?>
期正在火热进行中…
		<a href="/one/phase/detail?id=<?php echo $_smarty_tpl->tpl_vars['lastPhase']->value['id'];?>
">立即前往</a>
	</div>
	<?php }?>
	<!--下一期 /-->
</body>
</html>
<script src='<?php echo @URL_JS;?>
one/vender/jquery.min.js'></script>
<script src='<?php echo @URL_JS;?>
one/vender/amazeui.min.js'></script>
<script src="<?php echo @URL_JS;?>
one/phase/fn.js"></script>
<script src="<?php echo @URL_JS;?>
one/phase/product.js"></script>
<?php }} ?>