<?php /* Smarty version Smarty-3.1.11, created on 2016-08-18 10:41:13
         compiled from "app\View\template\default\index\index\index.mobile.tpl" */ ?>
<?php /*%%SmartyHeaderCode:735657ac0e814531a5-35520199%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd9daa6ebfc90cbe724d81d3e9ea7afc0dfe863f1' => 
    array (
      0 => 'app\\View\\template\\default\\index\\index\\index.mobile.tpl',
      1 => 1471485529,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '735657ac0e814531a5-35520199',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_57ac0e8161aa17_87940954',
  'variables' => 
  array (
    'positions' => 0,
    'row' => 0,
    'postition3' => 0,
    'jxuan_product' => 0,
    'i' => 0,
    'cjy_product' => 0,
    'gny_product' => 0,
    'zyx_product' => 0,
    'user' => 0,
    'site' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ac0e8161aa17_87940954')) {function content_57ac0e8161aa17_87940954($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("public/fr/header.mobile.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	<link rel="stylesheet" href="<?php echo @URL_CSS;?>
index/index/index.mobile.css">
</head>
<body class="">
	<!--头部-->
  <header class="mui-bar mui-bar-nav index">
		<div class="header_logo"><img src="<?php echo @URL_IMG;?>
/wx/index_logo.png"></div>
		<div class="header_change_city"><a href="#">温州</a></div>
		<a class="open_rightb" id="openRightb"></a>
		<div class="header_seach">
			<a href="/product/product/search"><div class="header_seach_box"><i class="inedx_search"></i> 搜索关键字</div></a>
		</div>
  </header>
	<!--头部 /-->
	<div class="mui-content">
		<!--banner图-->
		<div class="banner">
			<div id="slider" class="swipe" style="visibility:visible;">
				<div class="swipe-wrap">
				    <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['positions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
					<figure><div><a href="<?php echo $_smarty_tpl->tpl_vars['row']->value['url'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['row']->value['image'];?>
" width="100%" /></a></div></figure>
					<?php } ?>
				</div>
			</div>
			<nav>
			<ul id="position">
		<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['positions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
         <li class=""></li>
         	<?php } ?>
			</ul>
		</nav>

			<script src="<?php echo @URL_JS;?>
index/index/swipe.js"></script>
			<script>
				var bullets = document.getElementById('position').getElementsByTagName('li');
				bullets[0].className='on';
				var slider = new Swipe(document.getElementById('slider'), {
					auto: 3000,
					continuous: true,
          callback: function (pos) {
              var i = bullets.length;
              while (i--) {
                  bullets[i].className = ' ';
              }
              bullets[pos].className = 'on';
          }
				});

			</script>
		</div>
		<!--banner图 /-->
		<!--分类-->
		<div class="index_type">
		<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['postition3']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
			<a href="/product/product/list?tourism_type=<?php echo $_smarty_tpl->tpl_vars['row']->value['properties']['params']['tourism_type'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['row']->value['properties']['image'];?>
"><p><?php echo $_smarty_tpl->tpl_vars['row']->value['properties']['title'];?>
</p></a>
		<?php } ?>
		</div>
		<!--分类 /-->
		<div class="line"></div>
		<!--友趣游精选-->
		<div class="view">
			<div class="view_header bd_b bl">友趣游精选</div>
			<div class="view_content">
					<ul class="relevant_prod_list">
					        <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['jxuan_product']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
        		<?php if ($_smarty_tpl->tpl_vars['i']->value<4){?>
						<li><a href="/product/product/detail?id=<?php echo $_smarty_tpl->tpl_vars['row']->value['productInfo']['product_id'];?>
">
						<div class="img"><img class="lazy" src="<?php echo $_smarty_tpl->tpl_vars['row']->value['productInfo']['image'];?>
"></div>
						<div class="name"><?php echo $_smarty_tpl->tpl_vars['row']->value['productInfo']['product_name'];?>
</div>
						<div class="price">￥<strong><?php echo $_smarty_tpl->tpl_vars['row']->value['productInfo']['price'];?>
</strong></div></a></li>
				<?php }?>
          <?php } ?>	
					</ul>
			</div>
		</div>
		<!--友趣游精选 /-->
		<div class="line"></div>
		<!--tab-->
		<div class="mui-segmented-control">
		      <a href="#chujingyou" class="mui-control-item mui-active">出境游</a>
		            <a href="#guoneiyou" class="mui-control-item">国内游</a>
      <a href="#youlun" class="mui-control-item ">自由行</a>

    </div>
		<div class="tabs">
		
		      <div id="chujingyou" class="mui-control-content mui-active">
        <div class="index_prod_content">
					<ul class="list-container">
            <?php if ($_smarty_tpl->tpl_vars['cjy_product']->value){?>
    <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['cjy_product']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
						<li class="index_prod_ali"><a href="/product/product/detail?id=<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
">
								<div 	class="prod_img">
									<div class="prod_count">
										距结束：<span class="count_time" data-time="<?php echo $_smarty_tpl->tpl_vars['row']->value['down_time']-time();?>
">获取中...</span>
									</div>
									<img class="lazy" src="<?php echo $_smarty_tpl->tpl_vars['row']->value['image'];?>
">
									<p>出发日期：<?php echo date("Y-m-d",$_smarty_tpl->tpl_vars['row']->value['travel_date']);?>
</p>
								</div>
								<div class="prod_info">
									<h3><?php echo $_smarty_tpl->tpl_vars['row']->value['product_name'];?>
</h3>
									<div class="price">
										￥<strong class="m_r5"><?php echo $_smarty_tpl->tpl_vars['row']->value['price'];?>
</strong>
										<!-- 起
										<del class="m_r5">市场价：￥<?php echo $_smarty_tpl->tpl_vars['row']->value['cost_price'];?>
</del> -->
									</div>
								</div></a></li>
        <?php } ?>
        <?php }?>
					</ul>
        </div>
      </div>
		
		      <div id="guoneiyou" class="mui-control-content">
        <div class="index_prod_content">
					<ul class="list-container">
					      <?php if ($_smarty_tpl->tpl_vars['gny_product']->value){?>
    <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['gny_product']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
						<li class="index_prod_ali"><a href="/product/product/detail?id=<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
">
						<div class="prod_img">
									<div class="prod_count">
										距结束：<span class="count_time" data-time="<?php echo $_smarty_tpl->tpl_vars['row']->value['down_time']-time();?>
">获取中...</span>
									</div>
									<img class="lazy" src="<?php echo $_smarty_tpl->tpl_vars['row']->value['image'];?>
">
									<p>出发日期：<?php echo date("Y-m-d",$_smarty_tpl->tpl_vars['row']->value['travel_date']);?>
</p>
								</div>
								<div class="prod_info">
									<h3><?php echo $_smarty_tpl->tpl_vars['row']->value['product_name'];?>
</h3>
									<div class="price">
										￥<strong class="m_r5"><?php echo $_smarty_tpl->tpl_vars['row']->value['price'];?>
</strong>
										<!--  起<del class="m_r5">市场价：￥<?php echo $_smarty_tpl->tpl_vars['row']->value['cost_price'];?>
</del>-->
									</div>
								</div></a></li>
        <?php } ?>
        <?php }?>
					</ul>
        </div>
      </div>
		
      <div id="youlun" class="mui-control-content ">
        <div class="index_prod_content">
					<ul class="list-container">
      <?php if ($_smarty_tpl->tpl_vars['zyx_product']->value){?>
      <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['zyx_product']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
						<li class="index_prod_ali"><a href="/product/product/detail?id=<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
">
							<div class="prod_img">
								<div class="prod_count">距结束：<span class="count_time" data-time="<?php echo $_smarty_tpl->tpl_vars['row']->value['down_time']-time();?>
">获取中...</span></div>
								<img class="lazy" src="<?php echo $_smarty_tpl->tpl_vars['row']->value['image'];?>
"><p>出发日期：<?php echo date("Y-m-d",$_smarty_tpl->tpl_vars['row']->value['travel_date']);?>
</p>
							</div>
							<div class="prod_info">
								<h3><?php echo $_smarty_tpl->tpl_vars['row']->value['product_name'];?>
</h3>
								<div class="price">￥<strong class="m_r5"><?php echo $_smarty_tpl->tpl_vars['row']->value['price'];?>
</strong>
							<!--起 <del class="m_r5">市场价：￥<?php echo $_smarty_tpl->tpl_vars['row']->value['cost_price'];?>
</del> -->
								</div>
							</div>
						</a></li>
        <?php } ?>
			<?php }?>

					</ul>
        </div>
      </div>


    </div>
		<!--tab /-->
		<!--底部-->
		<div class="index_footer">
			<div class="index_footer_a bd_b">
			<?php if ($_smarty_tpl->tpl_vars['user']->value->account!=''){?>
				<a href="#">个人中心</a>
				<a href="/user/account/logout">注销</a>
				<?php }else{ ?>
				<a href="/user/account/login">登录</a>
				<a href="/user/account/register">注册</a>
				<?php }?>
				<a href="#">客户端</a>
				<a href="#">客服</a>
			</div>
			<p><?php echo $_smarty_tpl->tpl_vars['site']->value['copyright'];?>
</p>
		</div>
		<!--底部 /-->
	</div>


  <!--侧栏-->
  <div class="side_right" id='panelRight'>
		<dl>
			<dt>

			        <div class="user_img"><img src="<?php if ($_smarty_tpl->tpl_vars['user']->value->iavatar!=''){?> <?php echo $_smarty_tpl->tpl_vars['user']->value->avatar;?>
 <?php }else{ ?>  <?php echo @URL_IMG;?>
/wx/user_img.png <?php }?>"></div>
			        <?php if ($_smarty_tpl->tpl_vars['user']->value->isLogin()){?>
								<?php if ($_smarty_tpl->tpl_vars['user']->value->member_name!=''){?>
								<a href="#">  <?php echo $_smarty_tpl->tpl_vars['user']->value->member_name;?>
 </a> 
								 <?php }elseif($_smarty_tpl->tpl_vars['user']->value->account!=''){?>  
								 <a href="#"> <?php echo $_smarty_tpl->tpl_vars['user']->value->account;?>
 </a>
								 <?php }?>
						 <?php }else{ ?>
						<a href="/user/account/login">请登录</a>
							<?php }?>
			</dt>
			<dd><img src="<?php echo @URL_IMG;?>
/wx/us.png"><a href="/static/html/aboutus?id=1925">关于我们</a></dd>
			<dd><img src="<?php echo @URL_IMG;?>
/wx/sysm.png"><a href="/static/html/aboutus?id=1926">使用说明</a></dd>
			<dd><img src="<?php echo @URL_IMG;?>
/wx/call.png"><a href="/static/html/aboutus?id=1927">联系我们</a></dd>
			<dd><img src="<?php echo @URL_IMG;?>
/wx/flgg.png"><a href="/static/html/aboutus?id=1928">法律公告</a></dd>
			<dd><img src="<?php echo @URL_IMG;?>
/wx/mzsm.png"><a href="/static/html/aboutus?id=1929">免责声明</a></dd>
			   <?php if ($_smarty_tpl->tpl_vars['user']->value->isLogin()){?>
			   <dd><img src="<?php echo @URL_IMG;?>
/wx/logout.png"><a href="/user/account/logout">退出登录</a></dd>
			   <?php }?>
		</dl>
  </div>
	<div class="mui-off-canvas-backdrop" id="zz"></div>
	<!--侧栏 /-->
<?php echo $_smarty_tpl->getSubTemplate ("public/fr/footer.mobile.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<script src='<?php echo @URL_JS;?>
index/index/jquery.min.js'></script>
<script src='<?php echo @URL_JS;?>
index/index/mui.min.js'></script>
<script src='<?php echo @URL_JS;?>
index/index/yqy.mobile.js'></script>
<script src='<?php echo @URL_JS;?>
index/index/index.mobile.js'></script>
<?php }} ?>