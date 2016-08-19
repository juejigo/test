"use strict";
$(function(){
	//根据图片高度，滚动时改变header背景
	var prodHeader=$("#prodHeader");
	var prodImg=$("#prodImg").height()-50;
	$(window).scroll(function(){
		if($(window).scrollTop()>=prodImg){
			prodHeader.removeClass("none");
		}else{
			prodHeader.addClass("none");
		}
	})
	//商家相册
	var h=parseInt(($(window).width()-20)/3);
	$(".shop_photos").find("li").css("width",h);
	
})
$("#itToggle").click(function(){
	var it=$("#imgText");
	if(it.css('display')=='none'){
		$(this).html('图文详情收起<i class="i_upg"></i>');
	}else if(it.css('display')=='block'){
		$(this).html('图文详情展开<i class="i_downg"></i>');
	}
	$("#imgText").slideToggle();
})

//收藏产品
function prodCollec(prodId){
	var e=$(".collection");
	$.ajax({
        url: "../testApi/success.php",
        data: { prodId: prodId }
    }).done(function(data){
        if (data.Success) {
            e.toggleClass("check");
            alert(data.Msg);
        }else {
        	alert(data.Msg);
        }
    }).fail(function(){ alert("网络错误"); });
}


//收藏商家
function shopCollec(shopId){
	var e=$(".collection");
	$.ajax({
        url: "../testApi/success.php",
        data: { shopId: shopId }
    }).done(function(data){
        if (data.Success) {
            e.toggleClass("check");
            alert(data.Msg);
        }else {
        	alert(data.Msg);
        }
    }).fail(function(){ alert("网络错误"); });
}
//点击相册显示
$(document).on("click",".shop_photos li",function(){
	var photo_box=$(".photo_box");
	var imgSrc=$(this).find("img").attr("src");
	$("body").css("overflow","hidden");
	photo_box.css("display","block").find("img").attr("src",imgSrc);
})
$(".photo_box").click(function(){
	$("body").css("overflow","visible");
	$(this).css("display","none");

})
