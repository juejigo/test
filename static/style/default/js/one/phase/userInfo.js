'use strict';
region.init($(".select_region"));
$("#userInfo").submit(function(e){
  e.preventDefault();
  var _this=$(this);
  var btn=$("button");
  var name=$("input[name='name']").val(),
      moblie=$("input[name='mobile']").val(),
      address=$("input[name='address']").val(),
      province_id=$("select[name='province_id']").val(),
      city_id=$("select[name='city_id']").val(),
      county_id=$("select[name='county_id']").val();
  if(!name){
    nAlert("姓名不能为空");
    return false;
  }
  if(!isPhone(moblie)){
    nAlert("输入正确的手机号码");
    return false;
  }
  if(province_id==0){
    nAlert("省不能为空");
    return false;
  }
  if(city_id==0){
    nAlert("市能为空");
    return false;
  }
  if(county_id==0){
    nAlert("区不能为空");
    return false;
  }
  if(!address){
    nAlert("地址不能为空");
    return false;
  }
  btn.attr("disabled","disabled").html("正在保存...")
  $.post('/oneuc/member/ajax?op=saveinfo',_this.serialize(),function(data){
    if(data.errno==0){
      btn.removeAttr("disabled","disabled").html("保存信息")
      nAlert("保存成功");
    }else{
      btn.removeAttr("disabled","disabled").html("保存信息")
      nAlert("保存失败");
    }
  })

})
/**
 * 验证手机号码
 * @param  {[type]}  num 手机号码
 */
function isPhone(num){
    var mobile = /^1[0-9]{10}$/;
    return (mobile.exec(num)) ? true : false;
}
