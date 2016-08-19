'use strict';
/**
 * 取消收藏
 * @param  {[type]} id 商品ID
 * @return {[type]}    [description]
 */
function collectCancel(id,e){
  var _this=$(e).parents("li");
  $.post("/favorite/favorite/cancle",{favorite_id :id},function(data){
    if(data.errno==0){
      _this.animate({"opacity":"0"},500,function(){
        _this.remove();
      })
    }
  },"json")
}
/**
 * 绑定时间
 */
if($("#userInfo").length>0){
  $("#birthday").datetimepicker({
      language: 'zh',
      autoclose: true,
      minView: "month",
      format: "yyyy-mm-dd",
      pickerPosition: "top-right"
  })
  region.init($(".select_region"));
}

/**
 * 选择图片
 * @param  {[type]} document [description]
 * @return {[type]}          [description]
 */
$(document).on('change','#uploadInput',function(){
	var _this=$(this),
      imgDom=$("#userImg"),
			id=$("#id").val();
	var files = this.files,
			base64,
			reader = new FileReader();
	reader.readAsDataURL(files[0]);
	reader.onload = function (e) {
	   base64 = e.target.result;
     if (files) {
         var file = files[0];
         if (!/\.(jpg|jpeg|png|GIF|JPG|PNG)$/.test(file.name)) {
             alert("图片类型必须是jpeg,jpg,png中的一种");
             return false;
         }
         if (file.size > 1 * 1024 * 1024) {
          alert("上传图片过大");
             return false;
          }
          uploadFile(imgDom);
    }
	};
})
/**
 * 上传头像
 * @param  {[type]} base64 [description]
 * @param  {[type]} img    [description]
 * @param  {[type]} id     [description]
 * @return {[type]}        [description]
 */
function uploadFile(img){
		var form=document.forms[0];
		var formData = new FormData(form);
		$.ajax({
		    url : '/user/profile/avatar',
		    type : "POST",
		    data : formData,
		    dataType:"json",
		    processData : false,
		    contentType : false,
		}).done(function(data){
				if(data.errno==0){
						img.attr('src',data.img);
				}
		})
}
var form=$('#userInfo');
form.find('input').change(function () {
    formSubmit();
})
$('.sex_change').click(function () {
   var _this=$(this),
       val=_this.data("sex");
   if(_this.hasClass("checked")){
     return false;
   }
   _this.addClass('checked').siblings().removeClass('checked');
   $("#sex").val(val);
   formSubmit();
});
$('.select_region select').change(function () {
    var province = $("#province_id").val();
    var city = $("#city_id").val();
    var county = $("#county_id").val();
    if (province!=0 && city!=0 && county!=0) {
        formSubmit();
    }
});
/**
 * 表单提交
 * @return {[type]} [description]
 */
function formSubmit(){
  $.post("/user/member/ajax?op=edit_userinfo",form.serialize(),function(data){
    if(data.errno==0){
      layer.msg('保存成功！');
    }else{
    	layer.msg(data.errmsg);
    }
  },"json")
}
