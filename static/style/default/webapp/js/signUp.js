"use strict";
//上传图片
$(document).on('change','#upImg',function(){
	var files = this.files;
	if (files) {
        var file = files[0];
        if (!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG)$/.test(file.name)) {
            alert("图片类型必须是.gif,jpeg,jpg,png中的一种");
            return false;
        }
        if (file.size > 10 * 1024 * 1024) {		//10M
        	alert("上传图片过大");
            return false;
        }
        $("#uploadImg").ajaxSubmit({
	        type:'post',
	        url: "/vote/player/image",    
	        success: function(data){        
				if (data.errno == 0) {
	                $("#upImg").next().val(data.img);
		            $("#upImg").next().next().attr('src', data.img);
		        } else {
		            alert(data.errmsg);
		        }
	        }
	    });
    }
});

//报名
var signUp=function(){
	var realname=$("#realname").val(),			//真实姓名
		phone=$("#phone").val(),				//手机号码
		photo=$("#photo").val(),				//个人照片
		manifesto=$("#manifesto").val();		//参赛宣言
	if(!realname){
		alert("请输入真实姓名！");
		return false;
	}else if(!phone.match(/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/)){
		alert("请输入正确的手机号码！");
		return false;
	}else if(!photo){
		alert("请上传1张个人照片！");
		return false;
	}else if(!manifesto){
		alert("请输入参赛宣言！");
		return false;
	}
	$.ajax({
		url:"/vote/player/add",
		data: {
			vote_id:VOTEID,
        	name:realname,
        	phone:phone,
        	image:photo,
        	declaration:manifesto
        }
	}).done(function(data){
        if (data.errno == 0) {
        	successTip("sign_up","报名成功","感谢您的关注和支持",1500,function(){
        		window.location='/vote/vote?vote_id='+VOTEID+'';
        	});
        } else {
        	alert(data.errmsg);
        }
    });
}
//重新报名
function againShow(){
	var form=$(".sign_up_again");
	form.css({"z-index":5,"display":"block","height":form.height()+20}).animate({opacity : 1},500);
}