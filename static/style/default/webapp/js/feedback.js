/* 列表页 */
//获取更多信息
function get_more(){
	  p = Number(p)+1;
	  //alert(p);return;
	  $.ajax({
			cache: true,
			dataType:'json',
			type: "GET",
			url:URL+'product/feedback/list',
			data:'ajax=1&page='+p+'&product_id='+product_id+'&grade='+grade,
			async: false,
			error: function(request) {
				alert("系统繁忙");
			},
			success: function(data) {
				//alert(data.feedback_list.length);return;
				if(data.feedback_list.length>0){
				  var html = '';
				  var feedback_list = data.feedback_list;
				  for(i=0;i<feedback_list.length;i++){
					 html += '<div class="bl_box"><div class="blbox_tt"><span class="star'+feedback_list[i].grade+'"></span><em>'+feedback_list[i].member_name+'</em>'+feedback_list[i].dateline+'</div><div class="blbox_info">'+feedback_list[i].content+'</div></div>';				  
				  }
				  html += '';
				  $('.bbs_list').append(html);
				}
			}
	}); 

}