<?php /* Smarty version Smarty-3.1.11, created on 2016-08-18 10:04:55
         compiled from "app\View\template\default\public\fr\footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1373757abec623b2541-73888036%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1ced2463f2327eec24cc5c9507680bb9c7eb9cc0' => 
    array (
      0 => 'app\\View\\template\\default\\public\\fr\\footer.tpl',
      1 => 1471485534,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1373757abec623b2541-73888036',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_57abec6240cbb3_67417842',
  'variables' => 
  array (
    'site' => 0,
    'module' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57abec6240cbb3_67417842')) {function content_57abec6240cbb3_67417842($_smarty_tpl) {?><!--底部-->
  <div class="footer">
    <div class="footer_nav">
        <div class="footer_nav_main">
          <ul>
            <li><a href="/static/html/aboutus?id=1925">关于我们</a></li>
            <li><a href="/static/html/aboutus?id=1926">使用说明</a></li>
            <li><a href="/static/html/aboutus?id=1927">联系我们</a></li>
            <li><a href="/static/html/aboutus?id=1928">法律公告</a></li>
            <li><a href="/static/html/aboutus?id=1929">免费声明</a></li>
          </ul>
          <div class="go_top" onclick="goTop();"></div>
        </div>
    </div>
    <div class="footer_main">
      <div class="footer_info">
        <img src="<?php echo @URL_MIX;?>
company/images/footer_logo.png" class="logo">
        <div class="info">
          <p>服务热线</p>
          <p class="bg"><img src="<?php echo @URL_MIX;?>
company/images/telkf.png"></p>
          <p>地址：<?php echo $_smarty_tpl->tpl_vars['site']->value['address'];?>
</p>
          <p>电话：<?php echo $_smarty_tpl->tpl_vars['site']->value['telephone'];?>
</p>
        </div>
        <div class="ewm">
          <img src="<?php echo $_smarty_tpl->tpl_vars['site']->value['app_image'];?>
">
          <p>手机APP</p>
        </div>
        <div class="ewm">
          <img src="<?php echo $_smarty_tpl->tpl_vars['site']->value['weixin_image'];?>
">
          <p>微信</p>
        </div>
      </div>
      <div class="footer_copyright"><?php echo $_smarty_tpl->tpl_vars['site']->value['copyright'];?>
</div>
    </div>
  </div>
  <!--底部/-->
  </body>
</html>
<script src="<?php echo @URL_JS;?>
lib/jquery-1.9.1.min.js"></script>
<script src="<?php echo @URL_JS;?>
index/index/datetimepicker.min.js"></script>
<script src="<?php echo @URL_JS;?>
index/index/jquery.SuperSlide.js"></script>
<script src="<?php echo @URL_JS;?>
index/index/lazyload.min.js"></script>
<script type="text/javascript" src="<?php echo @URL_JS;?>
public/fr.js"></script>
<script type="text/javascript" src="<?php echo @URL_JS;?>
public/<?php echo $_smarty_tpl->tpl_vars['module']->value;?>
.js"></script>

<!-- 美恰 -->
  <script type='text/javascript'>
    (function(m, ei, q, i, a, j, s) {
        m[a] = m[a] || function() {
            (m[a].a = m[a].a || []).push(arguments)
        };
        j = ei.createElement(q),
            s = ei.getElementsByTagName(q)[0];
        j.async = true;
        j.charset = 'UTF-8';
        j.src = i + '?v=' + new Date().getUTCDate();
        s.parentNode.insertBefore(j, s);
    })(window, document, 'script', '//static.meiqia.com/dist/meiqia.js', '_MEIQIA');
    _MEIQIA('entId', 25304);
</script>
<!-- 站长统计 -->
<div style="display:none;">
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1260159051'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s11.cnzz.com/z_stat.php%3Fid%3D1260159051 ' type='text/javascript'%3E%3C/script%3E"));</script>
</div>


<?php }} ?>