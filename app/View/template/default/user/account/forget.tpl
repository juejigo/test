<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<title>{$headerTitle}</title>
<meta content="{$headerKeywords}" name="Keywords">
<meta content="{$headerDescription}" name="Description">
  <link rel="stylesheet" href="{$smarty.const.URL_CSS}public/fr.css">
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
          <div class="name">找回密码</div>
        </div>
    </div>
    <!--头部 /-->
    <div class="login_bg">
      <!--找回密码框-->
      <div class="login_box">
          <div class="goto">已有帐号？<a href="/user/account/login">登录</a></div>
          <div class="login_title">找回密码</div>
            <form id="forgetForm">
              <div class="login_info">
                <div class="text_group">
                    <label class="label w200">手机号码：</label>
                    <div class="input">
                      <input type="text" name="phone" id="phone" placeholder="手机号码" class="w380">
                      <p class="error" for="phone"></p>
                    </div>
                </div>
                <div class="text_group">
                    <label class="label w200">验证码：</label>
                    <div class="input">
                      <input type="text" name="code" id="code" placeholder="验证码" class="w380">
                      <img src="/utility/captcha" class="code_img" onclick='this.src="/utility/captcha?"+Math.random()'>
                      <p class="error" for="code"></p>
                    </div>
                </div>
                <div class="text_group">
                    <label class="label w200">校验码：</label>
                    <div class="input">
                      <input type="text" name="phone_code" id="phoneCode" placeholder="手机收到的校验码" class="w380">
                      <button type="button" class="get_code" onclick="getForgetPhoneCode(this)">获取动态密码</button>
                      <p class="error" for="phoneCode"></p>
                    </div>
                </div>
                <div class="text_group">
                    <label class="label w200">设置密码：</label>
                    <div class="input">
                      <input type="password" name="password" id="password" placeholder="密码" class="w380">
                      <p class="error" for="password"></p>
                    </div>
                </div>
                <div class="text_group">
                    <label class="label w200"></label>
                    <div class="input_nob w200">
                        <button type="button" class="btn btn_block btn_ycolor btn_raidus w300" onclick="forget(this);">确认提交</button>
                        <p class="error" for="all"></p>
                    </div>
                </div>
            </div>
          </form>
      </div>
      <!--找回密码框 /-->
    </div>
</body>
</html>
<script src="{$smarty.const.URL_JS}index/index/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_JS}index/index/login.min.js"></script>
