"use srtict";
//ajax动画
$.ajaxSetup({
    dataType: 'json',
    type: 'post',
	beforeSend : function() {
		loading.show();
	},
	complete : function() {
		loading.hide();
	}
});
//loading
var loading={
    dom: '<div class="loadingbox"><div class="loadecenter"><div class="loadrgb"><div class="loader"></div><p>请稍等...</p></div></div></div>',
	show:function(){
		$('body').append(loading.dom);
	},
	hide:function(){
		$('.loadingbox').remove();
	}
}
//手机号码正则
var p=/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/;

//点击发送验证码
$("#send_code").click(function(e){
	var _this=this
	var account=$("#account").val();		//手机号码
	if(!(account).match(p)){
		nAlert("请正确输入手机号码!");
		return false;
	}
	$.ajax({
        dataType:'json',
        type: "POST",
        data:'mobile='+account,
        url:'/user/account/sendcode',
        
    }).done(function(data){
        if (data.errno==0) {
            nAlert(data.errmsg);
            countdown(_this);
        } else {
            nAlert(data.errmsg);
        }
    });
})
//提交注册表单
$("#reg").click(function(){
	var account=$("#account").val(),		//手机号码
		code=$("#code").val(),
		password=$("#password").val();
	if(!(account).match(p)){
		nAlert("请正确输入手机号码！");
		return false;
	}else if(!code){
		nAlert("请输入验证码！");
		return false;
	}else if(!password){
		nAlert("请输入密码！");
		return false;
	}
	$('form').submit();
})
//倒计时
function countdown(evt){
	
	var $send_btn = $(evt),
		time = 3;
    $send_btn.attr("disabled", "disabled").addClass("disabled").html("重新发送（60）"); ;
    var timer = setInterval(function () {
        time--;
        if (time > 0) {
            $send_btn.html("重新发送（" + time + "）");
        } else {
            clearInterval(timer);
            $send_btn.removeAttr("disabled", "disabled").removeClass("disabled").html("发送验证码");
            return;
        }
    }, 1000)
}
//alert
function nAlert(msg,callBack){
    var box=$(".alert_box");
    var str='<div class="alert_box"><span class="alert_msg">'+msg+'</span></div>';
    if(box) box.remove();
    $("body").append(str);
    setTimeout(function(){
        $(".alert_box").animate({opacity : 0},500,function(){
            $(".alert_box").remove();
            if(callBack) callBack();
        })
    },1000)
}

