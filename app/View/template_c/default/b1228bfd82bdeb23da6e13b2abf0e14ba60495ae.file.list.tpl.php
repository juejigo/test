<?php /* Smarty version Smarty-3.1.11, created on 2016-08-11 13:48:37
         compiled from "app\View\template\default\one\phase\list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2120157ac11b5a8ef31-70328756%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b1228bfd82bdeb23da6e13b2abf0e14ba60495ae' => 
    array (
      0 => 'app\\View\\template\\default\\one\\phase\\list.tpl',
      1 => 1470645270,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2120157ac11b5a8ef31-70328756',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_57ac11b5ae90c0_45524020',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ac11b5ae90c0_45524020')) {function content_57ac11b5ae90c0_45524020($_smarty_tpl) {?><!DOCTYPE html>
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
	<title>商品列表</title>
	<link rel="stylesheet" href="<?php echo @URL_CSS;?>
one/phase/amazeui.css">
	<link rel="stylesheet" href="<?php echo @URL_CSS;?>
one/phase/fn.mobile.css">
  	<link rel="stylesheet" href="<?php echo @URL_CSS;?>
one/phase/product.css">
</head>
<body class="mhome">
<!--列表-->
<div class="list_box">
  <ul class="list" id="indexList">

  </ul>
  <div class="list_load"><span class="mui-spinner"></span></div>
</div>
<!--列表 /-->
<!--底部-->
<div data-am-widget="navbar" class="am-navbar am-cf am-navbar-yungou " id="">
  <ul class="am-navbar-nav am-cf am-avg-sm-4 bd_t">
      <li class="active">
        <a href="/one/phase/list">
            <img src="<?php echo @URL_IMG;?>
one/list2.png"/>
            <span class="am-navbar-label">列表</span>
        </a>
      </li>

      <li>
        <a href="/oneuc/member/index">
            <img src="<?php echo @URL_IMG;?>
one/user.png"/>
            <span class="am-navbar-label">我的</span>
        </a>
      </li>
  </ul>
</div>
<!--底部 /-->
</body>
</html>
<script src='<?php echo @URL_JS;?>
one/vender/jquery.min.js'></script>
<script src='<?php echo @URL_JS;?>
one/vender/amazeui.min.js'></script>
<script src="<?php echo @URL_JS;?>
one/phase/fn.js"></script>
<script src="<?php echo @URL_JS;?>
one/phase/product.js"></script><?php }} ?>