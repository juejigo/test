<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <title>友趣游</title>
  <link rel="stylesheet" href="{$smarty.const.URL_CSS}index/index/yqy.css">
  <link rel="stylesheet" href="{$smarty.const.URL_CSS}index/index/login.css">
</head>
<body>
    <!--头部-->
    <div class="login_nav">
        <div class="login_nav_main">
          <a class="logo" href="/index">
            <img src="{$smarty.const.URL_MIX}company/images/nav_logo.jpg" alt="logo">
            <img src="{$smarty.const.URL_MIX}company/images/nav_logo_sm.jpg">
          </a>
          <div class="name">登录</div>
        </div>
    </div>
    <!--头部 /-->
    <div class="login_bg">
      <!--登录框-->
      <div class="login_box" style="width:600px;">
          <div class="goto">还没有帐号？<a href="/index/index/register">注册</a></div>
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
                        <a href="/index/index/forget" class="ycolor fr">忘记密码？</a>
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
<script src="{$smarty.const.URL_JS}index/index/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_JS}index/index/login.min.js"></script>
