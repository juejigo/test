"use strict";
var p= /^1\d{10}$/,
    s= /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
var user={};

var h=$("#idenBox div").height();
$("#idenBox div div").height(h);


//上传图片
$(document).on('change','#upImg',function(){
    $(this).uploadimg(this);
});
//省市区三级联动
if($("#provinceList").length){
	provinceList.init($("#provinceList"));
}

//登陆
user.login=function(){
	var phone=$("#phone").val(),
		password=$("#password").val(),
		btn=$("button");
	if(!p.test(phone)){
		alert("请输入正确的手机号码！")
		return false;
	}else if(!password){
		alert("请输入密码！")
		return false;
	}
	btn.attr("disabled","disabled");
    $.ajax({
        url: "../testApi/success.php",
        data: {
        	phone:phone,
        	password:password
        }
    }).done(function(data){
        if (data.Success) {
            alert(data.Msg,function () {
                	window.location.href = "userindex.html"; 
            });
        } else {
        	btn.removeAttr("disabled","disabled");
            alert(data.Msg);
        }
    });
}
//注册
user.reg=function(){
	var phone=$("#phone").val(),
		code=$("#code").val(),
		password=$("#password").val(),
		btn=$("button");
	if(!p.test(phone)){
		alert("请输入正确的手机号码！")
		return false;
	}else if(!code){
		alert("请输入验证码！")
		return false;
	}else if(!password){
		alert("请输入密码！")
		return false;
	}
	btn.attr("disabled","disabled");
    $.ajax({
        url: "../testApi/success.php",
        data: {
        	phone:phone,
        	code:code,
        	password:password
        }
    }).done(function(data){
        if (data.Success) {
            alert(data.Msg,function () {
                	window.location.href = "login.html"; 
            });
        } else {
        	btn.removeAttr("disabled","disabled");
            alert(data.Msg);
        }
    });
}
//发送注册验证码
user.reg.code=function(evt){
	var phone=$("#phone").val();
	if(!p.test(phone)){
		alert("请输入正确的手机号码！")
		return false;
	}
	$.ajax({
        url: "../testApi/success.php",
        data: {
        	phone:phone
        }
    }).done(function(data){
        if (data.Success) {
            alert(data.Msg);
            user.countdown(evt);
        } else {
            alert(data.Msg);
        }
    });
}
//忘记密码
user.forget=function(){
	var phone=$("#phone").val(),
		code=$("#code").val(),
		password=$("#password").val(),
		btn=$("button");
	if(!p.test(phone)){
		alert("请输入正确的手机号码！")
		return false;
	}else if(!code){
		alert("请输入验证码！")
		return false;
	}else if(!password){
		alert("请输入密码！")
		return false;
	}
	btn.attr("disabled","disabled");
    $.ajax({
        url: "../testApi/success.php",
        data: {
        	phone:phone,
        	code:code,
        	password:password
        }
    }).done(function(data){
        if (data.Success) {
            alert(data.Msg,function () {
                	window.location.href = "login.html"; 
            });
        } else {
        	btn.removeAttr("disabled","disabled");
            alert(data.Msg);
        }
    });
}
//发送忘记密码验证码
user.forget.code=function(evt){
	var phone=$("#phone").val();
	if(!p.test(phone)){
		alert("请输入正确的手机号码！")
		return false;
	}
	$.ajax({
        url: "../testApi/success.php",
        data: {
        	phone:phone
        }
    }).done(function(data){
        if (data.Success) {
            alert(data.Msg);
            user.countdown(evt);
        } else {
            alert(data.Msg);
        }
    });
}
user.currentPhone=function(){
	var cPhonecode=$("#currentPhonecode").val();
	if(!cPhonecode){
		alert('请输入收到的验证码');
		return false;
	}
	$.ajax({
        url: "../testApi/success.php",
        data:{cPhonecode:cPhonecode}
    }).done(function(data){
        if (data.Success) {
            $("#editPhone").submit();
        } else {
            alert(data.Msg);
        }
    });
}
//发送当前手机验证码
user.currentPhone.code=function(evt){
	$.get('../testApi/success.php').done(function(data){
        if (data.Success) {
            alert(data.Msg);
            user.countdown(evt);
        } else {
            alert(data.Msg);
        }
    });
}
//我的信息
user.info=function(){
	var infoData=$("#userInfo").getFormparams();
    $.ajax({
        url: "../testApi/success.php",
        data:infoData
    }).done(function(data){
        if (data.Success) {
            alert(data.Msg);
        } else {
            alert(data.Msg);
        }
    });
}
//我的收藏
user.collEdit=function(){
    var coll_list=$(".coll_list"),
        btn=$(".input_btn");
    if(coll_list.hasClass('edit')){
        coll_list.removeClass('edit')
        btn.hide();
    }else{
        coll_list.addClass('edit');
        btn.show();
    }
}
user.collDel=function(){
    var check=$("input[type='checkbox']:checked"),arr=[];
    if(check.length==0){
        alert('请至少勾选一项');
        return false;
    }
    $.map(check,function(v,i){
        arr[i]=v.value;
    })
    $.ajax({
        url: "../testApi/success.php",
        data:{pordId:arr}
    }).done(function(data){
        if (data.Success) {
            alert(data.Msg,function(){
                window.location.reload();
            });
        } else {
            alert(data.Msg);
        }
    });
}
//实名认证
user.identity=function(){
    var realname=$("#realname").val(),
        idcard=$("#idcard").val(),
        img=$("#idcardImg").val();
    if(!realname){
        alert("请输入姓名");
        return false;
    }else if(!s.test(idcard)){
        alert("请输入正确的身份证号");
        return false;
    }else if (!img) {
        alert("请上传身份证");
        return false;
    }
    $.ajax({
        url: "../testApi/success.php",
        data:{
            realname:realname,
            idcard:idcard,
            img:img
        }
    }).done(function(data){
        if (data.Success) {
            alert(data.Msg,function () {
                window.location.href = "userindex.html"; 
            });
        } else {
            alert(data.Msg);
        }
    });
}
//修改密码
user.editPw=function(){
    var currentPassword=$("#currentPassword").val(),
        password=$("#password").val(),
        password2=$("#password2").val();
    if(!currentPassword){
        alert("请输入当前密码");
        return false;
    }else if(!password){
        alert("请输入新密码");
        return false;
    }else if (password != password2) {
        alert("两次密码不一致");
        return false;
    }
    $.ajax({
        url: "../testApi/success.php",
        data:{
            currentPassword:currentPassword,
            password:password,
        }
    }).done(function(data){
        if (data.Success) {
            alert(data.Msg,function () {
                window.location.href = "userindex.html"; 
            });
        } else {
            alert(data.Msg);
        }
    });
}
//倒计时
user.countdown=function(evt){
	var $send_btn = $(evt),
		time = 60;
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
//上传图片base64
$.fn.uploadimg=function(obj){
    var files = obj.files,
        imgurl;
    if (files) {
        var file = files[0];
        if (!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG)$/.test(file.name)) {
            alert("图片类型必须是.gif,jpeg,jpg,png中的一种");
            return false;
        }
        if (file.size > 10 * 1024 * 1024) {
            alert("图片不能大于10M");
            return false;
        }
    }
    var reader = new FileReader();
    reader.readAsDataURL(files[0]);
    reader.onload = function (e) {
        imgurl = e.target.result;
        $.post("../testApi/upimg.php",{img:imgurl}).done(function(data){
        	if (data.Success) {
                $(obj).next().val(data.img);
	            $(obj).next().next().attr('src', data.img);
	        } else {
	            alert(data.Msg);
	        }
        });
    };
};
//序列化表单
$.fn.getFormparams=function(){
    if (typeof this.serializeArray !== 'undefined') {
       var o = {};    
       var a = this.serializeArray();    
       $.each(a, function() {    
           if (o[this.name]) {    
               if (!o[this.name].push) {    
                   o[this.name] = [o[this.name]];    
               }    
               o[this.name].push(this.value || '');    
           } else {    
               o[this.name] = this.value || '';    
           }    
       });
       o=JSON.stringify(o);
       return o;
    }
};