getNav();
function getNav(){
	$("#menu").addClass("opno");
	$.post('/admincp/index/ajax?op=menu',function(data){
		createNav(data)
	},'JSON')

}
function createNav(data){
	$.each(data,function(i,t){
		var str='<div class="topbar-nav-item">';
		str+='<p class="topbar-nav-item-title">'+t.title+'</p>';
		str+='<ul>';
		$.each(t.nav,function(j,d){
			str+='<li><a href="'+d.url+'"><i class="'+d.icon+'"></i>'+d.name+'</a></li>';
		})
		str+='</ul></div>';
		var dom=getMin();
		dom.append(str);
	})
	$("#menu").removeClass("opno");
}
function getMin(){
	var bar = $("#topNav .topbar");
	var barMix =$("#topNav .topbar:first");
	bar.each(function(i,t){
		if($(t).height()<barMix.height()){
			barMix = $(t);
		}
	});
	return barMix;
}