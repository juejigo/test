"use srtict";
//替换alert
window.alert = nAlert;
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
//ajax配置
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
    dom: '<div class="loadingbox"><div class="loadecenter"><div class="loadrgb"><div class="loader"></div><p>正在加载，请稍等...</p></div></div></div>',
	show:function(){
		$('body').append(loading.dom);
	},
	hide:function(){
		$('.loadingbox').remove();
	}
}

var userId=$("#userId").val();		//获取用户ID
var adrsBox=$(".adrs_box");
/*
 *	刮刮卡相关方法
 *
 * */
var lottery={
	box:$(".lottery_box"),		//刮刮卡盒子
	getBefore:$(".get_before"),		//获取前
	getAfter:$("#getAfter"),		//获取后
	winNum:$(".info span"),
	//获取刮刮卡
	get:function(e){
		var _this=this;
		$.ajax({
			url:"/scrathuc/card/ajax?op=usecard",
			data:{
				userId:$("#userId").val()
			}
		}).done(function(data){
			if(data['errno']==1){
				$(e).animate({opacity : 0},200,function(){
						_this.getBefore.hide();
						$("#canvas").lotteryS(function(){
							if(data['winstart']==1){
								if(data['scrath_prize_id']!="")
								{
									$("#scrath_prize_id").val(data['scrath_prize_id']);
								}
								$.post('/scrathuc/card/ajax?op=weixin',{id:data['scrath_prize_id']});
								adrsBox.fadeIn(300);
								_this.winNum.text(data['prizecard']);	
							}
						});
						if(data['winstart']!=1){
							_this.getAfter.find("a").hide();
						}
						_this.getAfter.find("p").text(data['prize']);
						_this.getAfter.animate({opacity:1,zIndex:1},200);
			    });
			}else{
				nAlert("刮刮卡已用用光!");
			}
		}).fail(function(){
			nAlert("网络错误,确认网络是否已连接");
		})
	}
}
//地址框相关


function save(){
	var phone=$("#phone").val(),
	      prize_id=$("#scrath_prize_id").val();
	if(!phone.match(/^1[0-9]{10}$/)){
		nAlert("请输入正确的手机号码");
		return false;
	}
	$.ajax({
		url:"/scrathuc/card/ajax?op=address",
		data:{
			scrath_prize_id:prize_id,
			member_id:userId,
			phone:phone,
		}
	}).done(function(data){
		if(data['errno']==1){
			nAlert("保存成功",function(){
				adrsBox.fadeOut(300);
			})
		}
	})
}
//验证
function validate(){
	var num=$("#num").val();
	if(!num){
		nAlert("请输入验证码");
		return false;
	}
	$.ajax({
		url:"/scrathuc/card/ajax?op=verification",
		data:{
			redeem_code:num,
		}
	}).done(function(data){
		if(data['errno'] == 0){
			nAlert("验证成功",function(){
				window.location.reload();
			})
		}
		else
		{
			nAlert(data['errmsg']);
		}
	})
}