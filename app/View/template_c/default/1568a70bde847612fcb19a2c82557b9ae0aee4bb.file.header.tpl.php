<?php /* Smarty version Smarty-3.1.11, created on 2016-08-18 10:04:55
         compiled from "app\View\template\default\public\fr\header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2929957abec62231fc5-06706083%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1568a70bde847612fcb19a2c82557b9ae0aee4bb' => 
    array (
      0 => 'app\\View\\template\\default\\public\\fr\\header.tpl',
      1 => 1471485534,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2929957abec62231fc5-06706083',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_57abec62303436_97959973',
  'variables' => 
  array (
    'headerTitle' => 0,
    'headerKeywords' => 0,
    'headerDescription' => 0,
    'module' => 0,
    'user' => 0,
    'cate_id' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57abec62303436_97959973')) {function content_57abec62303436_97959973($_smarty_tpl) {?><!DOCTYPE html>
<head>
<title><?php echo $_smarty_tpl->tpl_vars['headerTitle']->value;?>
</title>
<meta content="<?php echo $_smarty_tpl->tpl_vars['headerKeywords']->value;?>
" name="Keywords">
<meta content="<?php echo $_smarty_tpl->tpl_vars['headerDescription']->value;?>
" name="Description">
<link type="image/gif" href="/favicon.gif" rel="shortcut icon">
<link type="image/gif" href="/favicon.gif" rel="bookmark">
<link rel="stylesheet" type="text/css" href="<?php echo @URL_CSS;?>
public/fr.css" />
<link rel="stylesheet" href="<?php echo @URL_CSS;?>
index/index/datetimepicker.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo @URL_CSS;?>
public/<?php echo $_smarty_tpl->tpl_vars['module']->value;?>
.css" />
</head>
<body>
<!--头部-->
  <div class="header">
    <div class="header_main">
      <div class="header_city"><b>温州</b><a href="#">[切换城市]</a></div>
      <div class="header_login">
      <?php if ($_smarty_tpl->tpl_vars['user']->value->isLogin()){?>
           <div class="header_info">你好，
           <?php if ($_smarty_tpl->tpl_vars['user']->value->member_name!=''){?>
        <?php echo $_smarty_tpl->tpl_vars['user']->value->member_name;?>

        	 <?php }elseif($_smarty_tpl->tpl_vars['user']->value->account!=''){?>  
        	  <?php echo $_smarty_tpl->tpl_vars['user']->value->account;?>

        	  <?php }?>  
       		 <i class="img"></i>
          <div class="header_info_box">
              <div class="header_info_down">
                <ul>
                  <li><a href="/order/order/list">我的订单</a></li>
                  <li><a href="/favorite/favorite/list">我的收藏</a></li>
                  <li><a href="/user/member/profile">账户设置</a></li>
                  <li><a href="/user/account/logout">退出</a></li>
                </ul>
              </div>
          </div>
        </div>
        <?php }else{ ?>
        <a href="/user/account/login">登录</a><span class="fg">|</span><a href="/user/account/register">免费注册</a>
        <?php }?>
      </div>
        <?php echo $_smarty_tpl->getSubTemplate ('public/fr/headermenu.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

    </div>
  </div>
  
  <!--头部 /-->
    <!--导航-->
  <div class="nav">
    <div class="nav_main">
      <a class="logo" href="/index/index">
        <img src="<?php echo @URL_MIX;?>
company/images/nav_logo.jpg" alt="logo">
        <img src="<?php echo @URL_MIX;?>
company/images/nav_logo_sm.jpg">
      </a>
      <ul>
        <li <?php if ($_smarty_tpl->tpl_vars['cate_id']->value==0){?> class="active" <?php }?>><a href="/index">首页</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cate_id']->value==455){?> class="active" <?php }?>><a href="/product/product/list?cate_id=455">自由行</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cate_id']->value==418){?> class="active" <?php }?>><a href="/product/product/list?cate_id=418">出境游</a></li>
        <li <?php if ($_smarty_tpl->tpl_vars['cate_id']->value==419){?> class="active"  <?php }?>><a href="/product/product/list?cate_id=419">国内游</a></li>
      </ul>
      <!--导航-搜索-->
      <div class="nav_search">
        <form action="/product/product/list" method="get">
          <input type="text" name="keyWord" placeholder="我想去..">
          <button type="submit"></button>
        </form>
      </div>
      <!--导航-搜索 /-->
      </div>
  </div>
  <!--导航 /--><?php }} ?>