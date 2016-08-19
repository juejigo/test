<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<meta content="{$headerKeywords}" name="Keywords">
<meta content="{$headerDescription}" name="Description">
<title>{$headerTitle}</title>
<link type="image/gif" href="/favicon.gif" rel="shortcut icon">
<link type="image/gif" href="/favicon.gif" rel="bookmark">
<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_CSS}public/fr.css" />
<link rel="stylesheet" href="{$smarty.const.URL_CSS}index/index/datetimepicker.min.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_CSS}public/{$module}.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.URL_CSS}public/user.css" />
</head>
<body>
  <!--头部-->
  <div class="header">
    <div class="header_main">
      <div class="header_city"><b>温州</b><a href="#">[切换城市]</a></div>
      <div class="header_login">
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
      </div>
        {include file='public/fr/headermenu.tpl'}
    </div>
  </div>
  <!--头部 /-->
  <!--我的导航栏-->
  <div class="user_nav">
    <div class="user_nav_main">
      <a href="/index" class="logo"><img src="{$smarty.const.URL_MIX}company/images/user_logo.png" alt="logo"></a>
      <ul>
        <li><a href="/index">首页</a></li>
        <li {if $from == 1} class="active"{/if}><a href="/order/order/list">我的订单</a></li>
        <li {if $from == 2} class="active"{/if}><a href="/favorite/favorite/list">我的收藏</a></li>
        <li {if $from == 3} class="active"{/if}><a href="/user/member/userinfo">账户设置</a></li>
      </ul>
    </div>
  </div>
  <!--我的导航栏 /-->