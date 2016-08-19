"use srtict";
//获取活动ID
var VOTEID=document.getElementById("voteId").value;
//获取屏幕高度
var WHEIGHT=$(window).height();
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
//替换alert
var time=0;
function alert(msg,callBack){
    var box=$(".alert_box");
    var str='<div class="alert_box"><span class="alert_msg">'+msg+'</span></div>';
    if(box) box.remove();
    $("body").append(str);
    time=setTimeout(function(){
        if(time!=0) clearInterval(time);
        $(".alert_box").animate({opacity : 0},500,function(){
            $(".alert_box").remove();
            if(callBack) callBack();
        })
    },1000)
}
//成功提示
function successTip(type,title,msg,time,callBack){
	var time=time?time:1000;
	var str='<div class="success_b"><div class="box"><div class='+type+'></div><h2>'+title+'</h2><p>'+msg+'</p></div></div>';
	$("body").append(str);
	time=setTimeout(function(){
        if(time!=0) clearInterval(time);
        $(".success_b").animate({opacity : 0},500,function(){
            $(".success_b").remove();
            if(callBack) callBack();
        })
    },time)
}
//搜索框
var searchBox=$(".common_search_box"),
	dialog=$(".dialog");
$("#showSearch").click(function(){
		dialog.fadeIn();
		searchBox.fadeIn();
})
dialog.on('touchstart',function(e){
	e.preventDefault();
	dialog.fadeOut();
	searchBox.fadeOut();
})
//获取地址栏参数
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
}
//活动弹出框
$("#toggleVote").click(function(){
	$(this).find("i").toggleClass("x180");
	$(".toggle_vote").slideToggle(200);
})
//收起展开
var tDoms=$("div[data-toggle]"),
	btn='<div class="t_doms"><i class="down"></i>展开</div>';
tDoms.each(function(i,d){
	var dHeight=$(d).height();
	if(dHeight>200){
		$(d).height("210").after(btn);
	}
})
$(document).on("click",".t_doms",function(){
	var _this=$(this).prev();
	$(this).addClass("hide").html('<i class="down"></i>收起');
	_this.css("height","");
})
$(document).on("click",".t_doms.hide",function(){
	var _this=$(this).prev();
	$(this).removeClass("hide").html('<i class="down"></i>展开');
	_this.css("height","210");
	$('html,body').animate({scrollTop:_this.offset().top},100);
})

