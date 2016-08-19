<?php /* Smarty version Smarty-3.1.11, created on 2016-08-18 10:04:55
         compiled from "app\View\template\default\index\index\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2429057abec62002997-45900849%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5ee225f3ef724c8fb264aea35bfddd5365f3c3f7' => 
    array (
      0 => 'app\\View\\template\\default\\index\\index\\index.tpl',
      1 => 1471485529,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2429057abec62002997-45900849',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_57abec6220dd81_89252043',
  'variables' => 
  array (
    'positions' => 0,
    'row' => 0,
    'keyWord' => 0,
    'jxuan_product' => 0,
    'i' => 0,
    'cjy_cate' => 0,
    'cjy_product' => 0,
    'gny_cate' => 0,
    'gny_product' => 0,
    'yl_cate' => 0,
    'zyx_product' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57abec6220dd81_89252043')) {function content_57abec6220dd81_89252043($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ('public/fr/header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

  <!--广告轮播图-->
  <div class="banner">
    <!-- <div class="swiper-container">
      <div class="swiper-wrapper">
        <div class="swiper-slide"><a href="#" style="background-image:url(../images/banner1.jpg)"></a></div>
        <div class="swiper-slide"><a href="#" style="background-image:url(../images/banner2.jpg)"></a></div>
        <div class="swiper-slide"><a href="#" style="background-image:url(../images/banner3.jpg)"></a></div>
      </div>
      <div class="swiper-prev"></div>
      <div class="swiper-next"></div>
    </div> -->
    <div class="slideBox">
      <div class="bd">
        <ul>
        <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['positions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
          <li><a href="<?php echo $_smarty_tpl->tpl_vars['row']->value['url'];?>
" style="background-image:url(<?php echo $_smarty_tpl->tpl_vars['row']->value['image'];?>
)"></a></li>
          <?php } ?>
        </ul>
      </div>
      <a class="prev" href="javascript:void(0)"></a>
			<a class="next" href="javascript:void(0)"></a>
    </div>
  </div>
  <!--广告轮播图 /-->
  <!--搜索-->
  <div class="search">
    <form action="/product/product/list" method="get">
      <div class="go_where"><input type="text" name="keyWord" placeholder="您想去哪儿？" value="<?php echo $_smarty_tpl->tpl_vars['keyWord']->value;?>
" ></div>
      <div class="go_time"><input type="text" name="go_time" placeholder="出发时间"></div>
      <button type="submit">准备，出发</button>
    </form>
  </div>
  <!--搜索  /-->
  <!--中间内容-->
  <div class="main">
    <!--精选-->
    <div class="index_prod">
      <h2 class="title">友趣游精选</h2>
      <p class="title_f"><a href="/product/product/list?id=<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
">查看更多 <i class="more"></i></a></p>
      <div class="choice">
        <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['jxuan_product']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
        <?php if ($_smarty_tpl->tpl_vars['i']->value==0){?>
        <div class="choice_left">
          <a href="/product/product/detail?id=<?php echo $_smarty_tpl->tpl_vars['row']->value['productInfo']['product_id'];?>
">
            <img data-original="<?php echo $_smarty_tpl->tpl_vars['row']->value['productInfo']['image'];?>
" class="lazy">
            <div class="choice_info">
              <p class="t"><?php echo $_smarty_tpl->tpl_vars['row']->value['productInfo']['product_name'];?>
</p>
              <p class="m">￥<?php echo $_smarty_tpl->tpl_vars['row']->value['productInfo']['price'];?>
起</p>
            </div>
          </a>
        </div>
        <?php }?>
          <?php } ?>
        <ul class="choice_right">
        <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['jxuan_product']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
        <?php if ($_smarty_tpl->tpl_vars['i']->value>0){?>
          <li>
            <a href="/product/product/detail?id=<?php echo $_smarty_tpl->tpl_vars['row']->value['productInfo']['id'];?>
">
              <img data-original="<?php echo $_smarty_tpl->tpl_vars['row']->value['productInfo']['image'];?>
" class="lazy">
              <div class="choice_info">
                <p class="t"><?php echo $_smarty_tpl->tpl_vars['row']->value['productInfo']['product_name'];?>
</p>
                <p class="m">￥<?php echo $_smarty_tpl->tpl_vars['row']->value['productInfo']['price'];?>
 起</p>
              </div>
            </a>
<?php }?>
          <?php } ?>
        </ul>
      </div>
    </div>
    <!--精选 /-->
    
    <!--出境游-->
    <div class="index_prod">
      <h2 class="title">出境游</h2>
      <p class="title_f"><a href="/product/product/list?cate_id=<?php echo $_smarty_tpl->tpl_vars['cjy_cate']->value;?>
">查看更多 <i class="more"></i></a></p>
      <ul class="prod_list">
            <?php if ($_smarty_tpl->tpl_vars['cjy_product']->value){?>
    <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['cjy_product']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
        <li>
          <a href="/product/product/detail?id=<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
">
            <div class="prod_img">
              <img data-original="<?php echo $_smarty_tpl->tpl_vars['row']->value['image'];?>
" class="lazy">
              <p class="month">出发日期：<?php echo date("Y-m-d",$_smarty_tpl->tpl_vars['row']->value['travel_date']);?>
</p>
            </div>
            <div class="pord_bor">
              <div class="prod_title"><?php echo $_smarty_tpl->tpl_vars['row']->value['product_name'];?>
</div>
              <div class="prod_price">￥<?php echo $_smarty_tpl->tpl_vars['row']->value['price'];?>
<span>起</span><del>市场价：￥<?php echo $_smarty_tpl->tpl_vars['row']->value['cost_price'];?>
</del></div>
              <div class="prod_end_time">距结束：<span data-time="<?php echo $_smarty_tpl->tpl_vars['row']->value['down_time']-time();?>
">读取中...</span></div>
            </div>
          </a>
        </li>
        <?php } ?>
        <?php }?>
      </ul>

    </div>
    <!--出境游 /-->
    <!--国内游-->
    <div class="index_prod">
      <h2 class="title">国内游</h2>
      <p class="title_f"><a href="/product/product/list?cate_id=<?php echo $_smarty_tpl->tpl_vars['gny_cate']->value;?>
">查看更多 <i class="more"></i></a></p>
      <ul class="prod_list">
      <?php if ($_smarty_tpl->tpl_vars['gny_product']->value){?>
    <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['gny_product']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
        <li>
          <a href="/product/product/detail?id=<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
">
            <div class="prod_img">
              <img data-original="<?php echo $_smarty_tpl->tpl_vars['row']->value['image'];?>
" class="lazy">
              <p class="month">出发日期：<?php echo date("Y-m-d",$_smarty_tpl->tpl_vars['row']->value['travel_date']);?>
</p>
            </div>
            <div class="pord_bor">
              <div class="prod_title"><?php echo $_smarty_tpl->tpl_vars['row']->value['product_name'];?>
</div>
              <div class="prod_price">￥<?php echo $_smarty_tpl->tpl_vars['row']->value['price'];?>
<span>起</span><del>市场价：￥<?php echo $_smarty_tpl->tpl_vars['row']->value['cost_price'];?>
</del></div>
              <div class="prod_end_time">距结束：<span data-time="<?php echo $_smarty_tpl->tpl_vars['row']->value['down_time']-time();?>
">读取中...</span></div>
            </div>
          </a>
        </li>
        <?php } ?>
        <?php }?>
      </ul>
    </div>
    <!--国内游 /-->
        <!--邮轮-->
    <div class="index_prod">
      <h2 class="title">自由行</h2>
      <p class="title_f"><a href="/product/product/list?cate_id=<?php echo $_smarty_tpl->tpl_vars['yl_cate']->value;?>
">查看更多 <i class="more"></i></a></p>
      <ul class="prod_list">
      <?php if ($_smarty_tpl->tpl_vars['zyx_product']->value){?>
      <?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['zyx_product']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
        <li>
          <a href="/product/product/detail?id=<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
">
            <div class="prod_img">
              <img data-original="<?php echo $_smarty_tpl->tpl_vars['row']->value['image'];?>
" class="lazy">
              <p class="month">出发日期：<?php echo date("Y-m-d",$_smarty_tpl->tpl_vars['row']->value['travel_date']);?>
</p>
            </div>
            <div class="pord_bor">
              <div class="prod_title"><?php echo $_smarty_tpl->tpl_vars['row']->value['product_name'];?>
</div>
              <div class="prod_price">￥<?php echo $_smarty_tpl->tpl_vars['row']->value['price'];?>
<span>起</span><del>市场价：￥<?php echo $_smarty_tpl->tpl_vars['row']->value['cost_price'];?>
</del></div>
              <div class="prod_end_time">距结束：<span data-time="<?php echo $_smarty_tpl->tpl_vars['row']->value['down_time']-time();?>
">读取中...</span></div>
            </div>
          </a>
        </li>
        <?php } ?>
        <?php }?>
      </ul>
    </div>
    <!--邮轮 /-->
  </div>
  <!--中间内容 /-->
   <?php echo $_smarty_tpl->getSubTemplate ('public/fr/footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>