
     {include file='public/fr/headeruser.tpl'}
        
  <!--设置列表-->
  <div class="main">
    <div class="user_set_box">
        <div class="user_set_left">
        	<form>
            <div class="user_img">
              <img src="{if $userinfo.avatar == ''}http://www.youquyou.cc/static/data/image/2016/06/07/16100362707.jpg{else}{$userinfo.avatar}{/if}" alt="" id="userImg">
              <p>效果预览</p>
            </div>
            <div class="upload_img">
              <div class="upload_btn">
                <input type="file" name="image" id="uploadInput">
              </div>
              <p>图片格式必须为以下格式：jpg, jpeg, png</p>
              <p>图片大小不可大于1M</p>
            </div>
            </form>
        </div>
        <div class="user_set_right">
 <form id="userInfo">
              <input type="hidden" id="id" value="{ user:12321}">
              <div class="text_group">
                  <label class="label w90">昵称：</label>
                  <div class="input">
                    <input type="text" name="member_name" id="member_name"  value="{$user->member_name}" placeholder="昵称">
                  </div>
              </div>
              <div class="text_group">
                  <label class="label w90">性别：</label>
                  <div class="input">
                    <div class="m_input">
                      <input type="hidden" name="sex" id="sex" value="{if $userinfo.sex != ''}{$userinfo.sex}{else}0{/if}">
                      <div class="sex_change {if $userinfo.sex == 1}checked{/if}" data-sex="1">男</div>
                      <div class="sex_change girl  {if $userinfo.sex == 0}checked{/if}"  data-sex="0">女</div>
                    </div>
                  </div>
              </div>
              <div class="text_group">
                  <label class="label w90">出生日期：</label>
                  <div class="input">
                    <input type="text" name="birthday" id="birthday" value="{if $birthday != ''}{date('Y-m-d',$birthday)}{/if}" placeholder="出生日期">
                    <i class="csrq"></i>
                  </div>
              </div>
              <div class="text_group">
                  <label class="label w90">省市区：</label>
                  <div class="input">
                    <div class="select_region" rel='["province":{$userinfo.province_id},"city":{$userinfo.city_id},"county":{$userinfo.county_id}]'>
                        <select name="province_id" id="province_id" class=""><option value="0">请选择</option></select>
                        <select name="city_id" id="city_id" class=""><option value="0">请选择</option></select>
                        <select name="county_id" id="county_id" class=""><option value="0">请选择</option></select>
                    </div>
                  </div>
              </div>
              <div class="text_group">
                  <label class="label w90">地址：</label>
                  <div class="input">
                    <input type="text" name="address" id="address"  value="{$userinfo.address}" placeholder="地址">
                  </div>
              </div>
            </form>
        </div>
    </div>
  </div>
  <!--设置列表 /-->
   {include file='public/fr/footer.tpl'}
<script src="{$smarty.const.URL_JS}index/index/datetimepicker.min.js"></script>
<script src="{$smarty.const.URL_JS}user/member/region.js"></script>
<script src="{$smarty.const.URL_JS}user/member/user.js"></script>
