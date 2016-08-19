<?php /* Smarty version Smarty-3.1.11, created on 2016-08-11 13:28:35
         compiled from "app\View\template\default\user\account\login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1285157ac0d03069e89-03233327%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '138e23db957ff3fd3737c70be6fe0b42a4e99e48' => 
    array (
      0 => 'app\\View\\template\\default\\user\\account\\login.tpl',
      1 => 1469178139,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1285157ac0d03069e89-03233327',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'headerTitle' => 0,
    'headerKeywords' => 0,
    'headerDescription' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_57ac0d030e2434_33487185',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ac0d030e2434_33487185')) {function content_57ac0d030e2434_33487185($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<title><?php echo $_smarty_tpl->tpl_vars['headerTitle']->value;?>
</title>
<meta content="<?php echo $_smarty_tpl->tpl_vars['headerKeywords']->value;?>
" name="Keywords">
<meta content="<?php echo $_smarty_tpl->tpl_vars['headerDescription']->value;?>
" name="Description">
  <link rel="stylesheet" href="<?php echo @URL_CSS;?>
public/fr.css">
  <link rel="stylesheet" href="<?php echo @URL_CSS;?>
index/index/login.css">
</head>
<body>
    <!--头部-->
    <div class="login_nav">
        <div class="login_nav_main">
          <a class="logo" href="/index">
            <img src="<?php echo @URL_MIX;?>
company/images/nav_logo.jpg" alt="logo">
            <img src="<?php echo @URL_MIX;?>
company/images/nav_logo_sm.jpg">
          </a>
          <div class="name">登录</div>
        </div>
    </div>
    <!--头部 /-->
    <div class="login_bg">
      <!--登录框-->
      <div class="login_box" style="width:600px;">
          <div class="goto">还没有帐号？<a href="/user/account/register">注册</a></div>
          <div class="login_title">登录</div>
            <form id="loginForm">
              <div class="login_info">
                <div class="text_group">
                    <label class="label w90">手机：</label>
                    <div class="input">
                      <input type="text" name="phone" id="phone" placeholder="手机号码" class="w380">
                      <p class="error" for="phone"></p>
                    </div>
                </div>
                <div class="text_group">
                    <label class="label w90">密码：</label>
                    <div class="input">
                      <input type="password" name="password" id="password" placeholder="密码" class="w380">
                      <p class="error" for="password"></p>
                    </div>
                </div>
                

<div class="text_group">
                    <label class="label w90"></label>
                    <div class="input_nob w425">
                        <div class="fl"><input type="checkbox" class="checkbox" checked>30天内自动登录</div>
                        <a href="/user/account/forget" class="ycolor fr">忘记密码？</a>
                    </div>
                </div>
                <div class="text_group">
                    <label class="label w90"></label>
                    <div class="input_nob w200">
                        <button type="button" class="btn btn_block btn_ycolor btn_raidus w300" onclick="login(this);">登录</button>
                        <p class="error" for='all'></p>
                    </div>
                </div>
            </div>
          </form>
      </div>
      <!--登录框 /-->
    </div>
</body>
</html>
<script src="<?php echo @URL_JS;?>
index/index/jquery-1.11.1.js"></script>
<script src="<?php echo @URL_JS;?>
index/index/login.min.js"></script>
<?php }} ?>