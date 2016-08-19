"use strict";
// var startX,startY,endX,endY;
// $('.prod_all').on('touchstart',function(evt){
// 	var touch=evt.originalEvent.touches[0];
// 	startX=touch.pageX;
// 	startY=touch.pageY;
	
// }).on('touchmove',function(evt){
// 	var touch=evt.originalEvent.touches[0];
// 	endX = (touch.pageX - startX);
// 	endY = (touch.pageY - startY);
// 	if (Math.abs(endX) < Math.abs(endY) && Math.abs(endY) > 10) {
// 		if (endY > 0) {
// 			$("header").fadeIn();
// 			$(".prod_class").fadeIn();
// 		} else {			
// 			$("header").fadeOut();
// 			$(".prod_class").fadeOut();
// 		}
// 	}
// })
//下拉菜单

var myScroll= new iScroll('list');
var list=$(".hide_list"),
	dialog=$(".dialog"),
	btn=$(".prod_class .all");
// $(window).scroll(function(){
// 	var s=$(this).scrollTop();
// 	if(s>=90){
// 		$("header").fadeOut("fast");
// 		$(".prod_class").fadeOut("fast");
// 	}else{
// 		$("header").fadeIn("fast");
// 		$(".prod_class").fadeIn("fast");
// 	}
// })
btn.click(function(e){
	if($(this).hasClass('show')){
		listHide();
	}else{

		$(this).addClass('show');
		dialog.fadeIn();
		list.fadeIn();
		$("html,body").addClass("tb_hide");
		myScroll.refresh();
	}
})
$(".dialog").on('touchstart',function(){
	listHide();
})
function listHide(){
	btn.removeClass('show');
	dialog.fadeOut();
	list.fadeOut();
	$("html,body").removeClass("tb_hide");
}
