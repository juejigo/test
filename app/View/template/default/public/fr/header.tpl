<!DOCTYPE html>
<head>
<title>{$headerTitle}</title>
<meta content="{$headerKeywords}" name="Keywords">
<meta content="{$headerDescription}" name="Description">
<link type="image/gif" href="/favicon.gif" rel="shortcut icon">
<link type="image/gif" href="/favicon.gif" rel="bookmark">
<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_CSS}public/fr.css" />
<link rel="stylesheet" href="{$smarty.const.URL_CSS}index/index/datetimepicker.min.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_CSS}public/{$module}.css" />
</head>
<body>
<!--头部-->
  <div class="header">
    <div class="header_main">
      <div class="header_city"><b>温州</b><a href="#">[切换城市]</a></div>
      <div class="header_login">
      {if $user->isLogin()}
           <div class="header_info">你好，
           {if $user->member_name != ''}
        {$user->member_name}
        	 {else if  $user->account != ""}  
        	  {$user->account}
        	  {/if}  
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
        {else}
        <a href="/user/account/login">登录</a><span class="fg">|</span><a href="/user/account/register">免费注册</a>
        {/if}
      </div>
        {include file='public/fr/headermenu.tpl'}
    </div>
  </div>
  
  <!--头部 /-->
    <!--导航-->
  <div class="nav">
    <div class="nav_main">
      <a class="logo" href="/index/index">
        <img src="{$smarty.const.URL_MIX}company/images/nav_logo.jpg" alt="logo">
        <img src="{$smarty.const.URL_MIX}company/images/nav_logo_sm.jpg">
      </a>
      <ul>
        <li {if $cate_id ==0 } class="active" {/if}><a href="/index">首页</a></li>
        <li {if $cate_id == 455 } class="active" {/if}><a href="/product/product/list?cate_id=455">自由行</a></li>
        <li {if $cate_id == 418 } class="active" {/if}><a href="/product/product/list?cate_id=418">出境游</a></li>
        <li {if $cate_id == 419 } class="active"  {/if}><a href="/product/product/list?cate_id=419">国内游</a></li>
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
  <!--导航 /-->