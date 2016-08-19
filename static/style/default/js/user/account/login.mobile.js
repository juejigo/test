'use strick';
var wH=$(window).height();
//打开图形码框
$("#open_code").click(function(e){
	var phone=$("#phone").val();		//手机号码
	if(!isPhone(phone)){
		mui.toast("请正确输入手机号码！");
		return false;
	}
	$('body').css({"position":"fixed","height":wH}).addClass("mui-off-canvas-wrap mui-active")
	$(".img_code_box").show();
})
//关闭图形码框
$("#close").click(function(e){
	$('body').css({"position":"static","height":"inherit"}).removeClass("mui-off-canvas-wrap mui-active")
	$(".img_code_box").hide();
})
//点击发送验证码
$("#send_code").click(function(e){
	var phone=$("#phone").val();		//手机号码
  var imgCode=$("#imgCode").val();		//手机号码
	if(!isPhone(phone)){
		mui.toast("请正确输入手机号码！");
		return false;
	}
  if(!imgCode){
		mui.toast("请输入图片验证码！");
		return false;
	}
	$.post("ajax?op=sendcode",{account:phone,verifycode:imgCode},function(data){
			if (data.errno==0) {
				$('body').css({"position":"static","height":"inherit"}).removeClass("mui-off-canvas-wrap mui-active")
				$(".img_code_box").hide();
					mui.toast('短信已经发送');
					countdown("#open_code");
			} else {
					mui.toast(data.errmsg);
			}
		},'json')
})
//提交注册表单
$("#register").click(function(){
	var phone=$("#phone").val(),		//手机号码
		code=$("#code").val(),
		password=$("#password").val(),
		btn=$(this);
	if(!isPhone(phone)){
		mui.toast("请正确输入手机号码！");
		return false;
	}else if(!code){
		mui.toast("请输入验证码！");
		return false;
	}else if(!password){
		mui.toast("请输入密码！");
		return false;
	}
	btn.attr("disabled","disabled");
	$.ajax({
        url: "ajax?op=register",    //注册url
				type:"post",
				dataType:"json",
        data: {
        	account:phone,
        	code:code,
        	password:password,
        }
    }).done(function(data){
        if (data.errno==0) {
        		mui.toast('注册成功,等待跳转')
						setTimeout(function(){
							window.location.href=data.url;
						},1000)
        } else {
						btn.removeAttr("disabled","disabled");
            mui.toast(data.errmsg);
        }
    });
})
//登录
$("#login").click(function(){
	var phone=$("#phone").val(),
		password=$("#password").val(),
		btn=$(this);
	if(!isPhone(phone)){
		mui.toast("请输入正确的手机号码！");
		return false;
	}else if(!password){
		mui.toast("请输入密码！");
		return false;
	}
	btn.attr("disabled","disabled");
    $.post("ajax?op=login",{account:phone,password:password},function(data){
    	if(data.errno==0){
				mui.toast('登录成功,等待跳转');
				setTimeout(function(){
					window.location.href="/index/index";
				},1000)
    	}else{
    		btn.removeAttr("disabled","disabled");
    		mui.toast(data.errmsg);
    	}
    },'json');
})


//发送找回密码信息
$("#send_forget_code").click(function(e){
	var _this=this
	var phone=$("#phone").val();		//手机号码
  var imgCode=$("#imgCode").val();		//手机号码
	if(!isPhone(phone)){
		mui.toast("请正确输入手机号码！");
		return false;
	}
  if(!imgCode){
		mui.toast("请输入图片验证码！");
		return false;
	}
	$.post("ajax?op=forgetsendcode",{account:phone,verifycode:imgCode},function(data){
			if (data.errno==0) {
					$('body').css({"position":"static","height":"inherit"}).removeClass("mui-off-canvas-wrap mui-active")
					$(".img_code_box").hide();
					mui.toast('短信已经发送');
					countdown("#open_code");
			} else {
					mui.toast(data.errmsg);
			}
		},'json')
})
//提交找回密码
$("#forget").click(function(){
	var phone=$("#phone").val(),		//手机号码
		code=$("#code").val(),
		password=$("#password").val(),
		btn=$(this);
	if(!isPhone(phone)){
		mui.toast("请正确输入手机号码！");
		return false;
	}else if(!code){
		mui.toast("请输入验证码！");
		return false;
	}else if(!password){
		mui.toast("请输入密码！");
		return false;
	}
	btn.attr("disabled","disabled");
	$.ajax({
        url: "ajax?op=forget",
				type:"post",
				dataType:"json",
        data: {
        	account:phone,
        	code:code,
        	password:password,
        }
    }).done(function(data){
        if (data.errno==0) {
        		mui.toast('找回成功,等待跳转')
						setTimeout(function(){
							window.location.href="/user/account/login";
						},1000)
        } else {
						btn.removeAttr("disabled","disabled");
            mui.toast(data.errmsg);
        }
    });
})
/**
 * 验证手机号码
 * @param  {[type]}  num 手机号码
 */
function isPhone(num){
    var mobile = /^1[0-9]{10}$/;
    return (mobile.exec(num)) ? true : false;
}

/**
 * 倒计时
 */
function countdown(evt){
	var $send_btn = $(evt),
		  time = 60;
   $send_btn.attr("disabled", "disabled").html("已发送 60"); ;
   var timer = setInterval(function () {
       time--;
       if (time > 0) {
           $send_btn.html("已发送 " + time);
       } else {
           clearInterval(timer);
           $send_btn.removeAttr("disabled", "disabled").html("发送验证码");
           return;
       }
   }, 1000)
}
