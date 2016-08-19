$(function(){
	$().UItoTop({ easingType: 'easeOutQuart' });
	addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } 
	$('#dowebok').responsiveSlides({
		pager: true,
		namespace: 'centered-btns',
	});
	var bannerUlWidth=$(".centered-btns_tabs").width();
	$(".centered-btns_tabs").css("margin-left",-bannerUlWidth/2);
})