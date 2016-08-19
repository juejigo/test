<?php /* Smarty version Smarty-3.1.11, created on 2016-08-11 11:09:22
         compiled from "app\View\template\default\public\fr\headermenu.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3000057abec62316263-81900533%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '974a99e67905cc72158fde431c8651f3d4fdc30e' => 
    array (
      0 => 'app\\View\\template\\default\\public\\fr\\headermenu.tpl',
      1 => 1469178139,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3000057abec62316263-81900533',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'site' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_57abec62393393_16201280',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57abec62393393_16201280')) {function content_57abec62393393_16201280($_smarty_tpl) {?>  <!--我的导航栏-->
      <div class="header_op">
        <ul>
          <li><a href="javascript:;"><span class="icon ph_64"></span></a>
            <div class="wx_64_box">
              <img src="<?php echo $_smarty_tpl->tpl_vars['site']->value['app_image'];?>
">
              <h2>下载友趣游APP</h2>
              <p>更多惊喜，等你来！</p>
            </div>
          </li>
          <li><a href="http://weibo.com/p/1006065824638888/home?from=page_100606&mod=TAB#place"><span class="icon wb_64"></span></a></li>
          <li><a href="javascript:;"><span class="icon wx_64"></span></a>
            <div class="wx_64_box">
              <img src="<?php echo $_smarty_tpl->tpl_vars['site']->value['weixin_image'];?>
">
              <h2>友趣游公众号</h2>
              <p>更多活动，扫我吧</p>
            </div>
          </li>
        </ul>
      </div>
      <div class="header_action">
        <a href="/order/order/list">我的订单</a><span class="fg">|</span><a href="/favorite/favorite/list">我的收藏</a>
      </div>
  <!--我的导航栏 /--><?php }} ?>