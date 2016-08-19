<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="renderer" content="webkit">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<title>我的</title>
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/amazeui.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/fn.mobile.css">
	<link rel="stylesheet" href="{$smarty.const.URL_CSS}one/phase/user.css">
</head>
<body class="mhome">
  <header data-am-widget="header" class="am-header am-header-default am-header-fixed">
      <div class="am-header-left am-header-nav">
          <a class="mui-icon-back mui-icon go_back" href="javascript:;"></a>
      </div>
      <h3 class="am-header-title">完善个人信息</h3>
  </header>
  <form id="userInfo">
    <div class="form_main bd_t bd_b">
      <div class="item">
          <input type="text" placeholder="姓名"  class="txt-input txt-password" name="name" value="{$addressInfo.name}">
      </div>
      <div class="item">
          <input type="tel" placeholder="手机号码"  class="txt-input txt-password" name="mobile" value="{$addressInfo.mobile}">
      </div>
      <div class="item">
				<div class="select_region" rel='["province":{if !empty($addressInfo.province_id)}{$addressInfo.province_id}{else}0{/if},"city":{if !empty($addressInfo.city_id)}{$addressInfo.city_id}{else}0{/if},"county":{if !empty($addressInfo.county_id)}{$addressInfo.county_id}{else}0{/if}]'>
						<select name="province_id" id="province_id" class=""><option value="0">省</option></select>
						<select name="city_id" id="city_id" class=""><option value="0">市</option></select>
						<select name="county_id" id="county_id" class=""><option value="0">区</option></select>
				</div>
      </div>
			<div class="item">
          <input type="tel" placeholder="邮编"  class="txt-input txt-password" name="post_code" value="{$addressInfo.post_code}">
      </div>
      <div class="item">
          <input type="text" placeholder="详细地址"  class="txt-input txt-password" name="address"  value="{$addressInfo.address}">
      </div>
      <div class="pay_btn">
        <button type="submit" class="am-btn am-btn-success am-btn-block">保存信息</button>
      </div>
    </div>
  </form>
</body>
</html>
<script src='{$smarty.const.URL_JS}one/vender/jquery.min.js'></script>
<script src='{$smarty.const.URL_JS}one/vender/amazeui.min.js'></script>
<script src="{$smarty.const.URL_JS}one/phase/fn.js"></script>
<script src="{$smarty.const.URL_JS}one/phase/region.js"></script>
<script src="{$smarty.const.URL_JS}one/phase/userInfo.js"></script>
