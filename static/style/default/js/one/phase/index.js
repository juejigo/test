"use strict";
$(function() {
  $('.am-slider').flexslider({
    directionNav: false,
  });
  sliderwords();
  countDown.init($(".countdown span"));
  setTimeout(function(){
		getList();
	},SETTIME)
});
/**
 * 文字轮播
 * @return {[type]} [description]
 */
function sliderwords(){
    var i = 0;
	setInterval(function(){
		if(i < ($('.words ul li').length - 1)){
			i++;
		} else {
			i= 0;
		}
		var show = $('.words ul li').eq(i).prop("outerHTML");
		$('.words ul li').eq(0).fadeOut(1000);
		$('.words ul li').eq(i).remove();
		$('.words ul').prepend(show);
		$('.words ul li').eq(0).fadeIn(1000);
	},3000);
}
/**
 * 滚动变浮动
 * @type {[type]}
 */
var listTab = $('#typeNav');
var listTabTop = listTab.offset();
var ul=$("#indexList");		//列表
var listLoading = false;
var listPage=$("#listPage");
function typeFied(){
    if(400 - $(window).scrollTop()  <= 0){
    	listTab.addClass('type_fixed');
    } else {
    	listTab.removeClass('type_fixed');
    }
}
$(document).on("click",".type_btn",function(){
	var _this=$(this);
	if(!_this.hasClass("checked")){
		$(".type_btn").removeClass("checked");
		_this.addClass("checked");
		setGetList();
		return false;
	}
	if(_this.hasClass("price") && _this.hasClass("checked")){
		if(_this.data("type")=="price_up"){
			_this.data("type","price_down");
		}else{
			_this.data("type","price_up");
		}
		setGetList();
	}
})
function setGetList(){
	ul.html('');
	listPage.val(1);
	listLoading=true;
	$(".list_load").html('<span class="mui-spinner"></span>');
	setTimeout(function(){
		getList();
	},SETTIME)
}
$(document).scroll(function() {
	if(!listLoading){
		scrollLoad();
	}
	typeFied();
});
/**
 * 滚动加载
 * @return {[type]} [description]
 */
function scrollLoad(){
	if ($(document).height() - $(window).height() - $(window).scrollTop() <= 30) {
		listLoading=true;
		setTimeout(function(){
			getList();
		},SETTIME)

    }
}
/**
 * 获取列表
 * @param  {[type]} type 类型默认default(人气)   new(最新)  hot(最热)
 * @return {[type]} [description]
 */
function getList() {
	var type=$("#typeNav").find(".checked").data("type");
	var page=listPage.val();
	$.post("../testApi/indexProdList.php",{type:type,page:page,class:'all'},function(data){
		if(data.errno==0){
			var str='';
			var l=data.product_list;
			$.each(l,function(i,t){
				str+='<li>';
				str+='<a href="product.html?id='+t.id+'" class="pic"><img class="lazy" src="'+t.image+'"><span class="xg">限购'+t.purchase+'次</span></a>';
				str+='<span class="title"><a href="product.html?id='+t.id+'">'+t.name+'</a></span>';
				str+='<div class="info_box flex-wrap">';
				str+='<div class="info flex-con">';
				str+='<div class="am-text-xs am-link-muted">总需'+t.need+'人次</div>';
				str+='<div class="am-progress am-progress-striped am-progress-xs am-active">';
				str+='<div class="am-progress-bar am-progress-bar-danger"  style="width: '+t.schedule+'"></div>';
				str+='</div>';
				str+='<div class="am-text-xs am-link-muted"><span class="am-fl">剩余<span class="am-text-danger">'+t.surplus+'</span></span></div>';
				str+='</div>';
				str+='<div class="btn"><img src="../image/addcart.png"></div>';
				str+='</div>';
				str+='</li>';
			})
			ul.append(str);
			if(l.length<10){
				$(".list_load").html("已经到最底了");
				listLoading=true;
			}else{
				listPage.val(parseInt(page)+1)
				listLoading=false;
			}


		}else{
			nAlert(data.errmsg)
		}
	},"json");
}
