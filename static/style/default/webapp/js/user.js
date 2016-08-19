"use strict";
$(function(){
	forMe.ajaxList();
	forOther.ajaxList();
})
//我的tab切换
var uTab=$(".user_tab").find("li"),
	ulist=$(".votes_list");
uTab.each(function(i,k){
	$(k).click(function(){
		if(!$(this).hasClass("check")){
			uTab.removeClass("check");
			$(this).addClass("check");
		}
		ulist.hide();
		ulist.eq(i).show();
	})
})

$(document).on("click",".det",function(){
	$(this).next().slideToggle(100);
})



$(document).on('scroll', function(){
	if(!forMeLoading ){
		if(forMeDom.css("display")=="block") forMe.loadMore();
	}
	if(!forOtherLoading ){
		if(forOtherDom.css("display")=="block") forOther.loadMore();
	}
})

//谁投给我相关方法
var forMeLoading = false,
	forMeDom=$('#forMe'),
	forMePage=$("#forMePage");
var forMe={
	loadMore:function(){
		var	bheight=$("body").height(),
			bsTop=$("body").scrollTop();
		if ( WHEIGHT + bsTop >= bheight - 60 ) { 
			forMeLoading = true;
			forMeDom.append('<div class="s_loading">加载中...</div>');
			forMe.ajaxList();
		} 
	},
	ajaxList:function(){
		$.post('/voteuc/vote/ajax?op=voteme',{vote_id:VOTEID,page:forMePage.val()}).done(function(data){
			if(data.errno == 0) {
				var str='';
				str+= data.html
				forMeDom.append(str);
				forMePage.val(data.PageIndex);
				forMeDom.find(".s_loading").remove();
				forMeLoading = false;
			}else{
				forMeDom.find(".s_loading").remove();
				forMeDom.append('<div class="s_loading">'+data.errmsg+'</div>');
			}
		});
	}
}
//我投给谁相关方法
var forOtherLoading = false,
	forOtherDom=$('#forOther'),
	forOtherPage=$("#forOtherPage");
var forOther={
	loadMore:function(){
		var	bheight=$("body").height(),
			bsTop=$("body").scrollTop();
		if ( WHEIGHT + bsTop >= bheight - 60 ) { 
			forOtherLoading = true;
			forOtherDom.append('<div class="s_loading">加载中...</div>');
			forOther.ajaxList();
		} 
	},
	ajaxList:function(){
		$.post('/voteuc/vote/ajax?op=myvote',{vote_id:VOTEID,page:forOtherPage.val()}).done(function(data){
			if(data.errno == 0){
				var str="";
				str+=data.html;
				forOtherDom.append(str);
				forOtherPage.val(data.PageIndex);
				forOtherDom.find(".s_loading").remove();
				forOtherLoading = false;
			}else{
				forOtherDom.find(".s_loading").remove();
				forOtherDom.append('<div class="s_loading">'+data.errmsg+'</div>');
			}
		});
	}
}


$("i.home").click(function(){
	var url= $(this).attr("url");
	window.location.href=url;
})