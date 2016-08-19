/* 搜索页 */
//组装URL
function search(){
	var go_url = URL+'product/product/list?';
	$('#area a').each(function(){
		var className = $(this).attr('class');
		//alert($(this).attr('val'));
		if(className=='cur'){
			area = $(this).attr('value');
		}
	});
	if(area!==''){
	  go_url += '&area='+area;
	}

	$('#cate_id a').each(function(){
		var className = $(this).attr('class');
		if(className=='cur'){
			cate_id = $(this).attr('value');
		}
	});
	//alert(cate_id);
	if(cate_id){
	  go_url += '&cate_id='+cate_id;
	}
	$('#cate_id2 a').each(function(){
		var className = $(this).attr('class');
		if(className=='cur'){
			cate_id2 = $(this).attr('value');
		}
	});
	if(cate_id2){
	  go_url += '&cate_id2='+cate_id2;
	}
	$('#cate_id3 a').each(function(){
		var className = $(this).attr('class');
		if(className=='cur'){
			cate_id3 = $(this).attr('value');
		}
	});
	if(cate_id3){
	  go_url += '&cate_id3='+cate_id3;
	}
	//alert(cate_id3);
	$('#brand_id a').each(function(){
		var className = $(this).attr('class');
		if(className=='cur'){
			brand_id = $(this).attr('value');
		}
	});
	if(brand_id){
	  go_url += '&brand_id='+brand_id;
	}
	if(size){
	  go_url += '&size='+size;
	}
	window.location = go_url;
	//alert(go_url);
}

//设置值
function set_val(field,val){
  //区域改变
  if(field=='area' && val!=area){
	  $.ajax({
			cache: true,
			dataType:'json',
			type: "POST",
			url:URL+'product/cate/list',
			data:'area='+val+'&level=1',
			async: false,
			error: function(request) {
				alert("Connection error");
			},
			success: function(data) {
				if(data.cate_list.length>0){
				  var cate_list = data.cate_list;
				  var level = cate_list[0].level;//分类等级
				  $('.cate_list').remove();//清除分类
				  var html = '<div class="sx_list_tj cate_list"><div class="sxtj_left">分类：</div><div class="sxtj_right2"><ul id="cate_id">';
				  for(i=0;i<cate_list.length;i++){
					 html += '<li><a href="javascript:;" onclick="set_val(\'cate_id\','+cate_list[i].id+');" id="cate_id_'+cate_list[i].id+'" value="'+cate_list[i].id+'">'+cate_list[i].cate_name+'</a></li>';				  
				  }
				  html += '</ul></div></div>';
				  $('#area_div').after(html);
				}
			}
	});   
  }
  selected = true; //默认选中读取子分类
  eval(field+' = '+val);
  $('#'+field+' li').each(function(){
	 var obj = $(this).find('a');
     var id = obj.attr('id');
	 if(id==field+'_'+val){
	   if(obj.attr('class')=='cur'){
	     obj.attr('class','');
		 eval(field+' = "0"');
		 selected = false;
	   }else{
	     obj.attr('class','cur');
	   }
	 }else{
	   obj.attr('class','');
	 }
  });

  //获取子分类
  if((field=='cate_id' || field=='cate_id2') && selected){
	  //alert(URL+'/product/cate/list');
	$.ajax({
		cache: true,
		dataType:'json',
		type: "POST",
		url:URL+'product/cate/list',
		data:'parent_id='+val,
		async: false,
		error: function(request) {
			alert("Connection error");
		},
		success: function(data) {
			if(data.cate_list.length>0){
			  var cate_list = data.cate_list;
			  var level = cate_list[0].level;//分类等级
			  var html = '<div class="sx_list_tj cate_list"><div class="sxtj_left">分类：</div><div class="sxtj_right2"><ul id="cate_id'+level+'">';
			  for(i=0;i<cate_list.length;i++){
				 html += '<li><a href="javascript:;" onclick="set_val(\'cate_id'+level+'\','+cate_list[i].id+');" id="cate_id'+level+'_'+cate_list[i].id+'" value="'+cate_list[i].id+'">'+cate_list[i].cate_name+'</a></li>';				  
			  }
			  html += '</ul></div></div>';
			  $('#'+field).parent().parent().nextAll('.cate_list').remove();
			  $('#'+field).parent().parent().after(html);
			}
		}
	});  
  }
}

/* 详情页 */

//设置哪种模式购买
function set_type(i){
	$('#light').show();
	$('#fade').show();
	type = i;
}

//购买数量  type 1减 2加
function update_num(type){
  if(type==1){
	 if(Number($('#shu').val())>1)$('#shu').val(Number($('#shu').val())-1);
  }else{
	 if(Number($('#shu').val())<stock){
		 $('#shu').val( Number($('#shu').val())+1);
	 }else{
		/*
		art.dialog({
			time: 2,
			content: '超过库存数量'
		});
		*/
		showMessage('超过库存数量',2000);
	 }
  }
}

/*
 * 产品收藏
 * var type 1收藏 2取消
 * var id 产品id
*/
function favorite(type,id){
	if(type==1){
		$.ajax({
			cache: true,
			dataType:'json',
			type: "POST",
			url:URL+'favorite/favorite/add',
			data:'dataid='+id+'&type=0',
			async: false,
			error: function(request) {
				showMessage('请先登录',1500);
			},
			success: function(data) {
				//alert(data);
				if(data.errno==0){
					/*
					art.dialog({
						time: 1.5,
						content: '收藏成功'
					});
					*/
					showMessage('收藏成功',1500);
					$('#favorite').removeClass('ysc');
					$('#favorite').attr('class','sc');
					$('#favorite').attr('onclick','favorite(2,'+data.id+')');
					return;
				}else{
					alert(data.errmsg);
				}
			}
		});	
	
	}else{
		$.ajax({
			cache: true,
			dataType:'json',
			type: "POST",
			url:URL+'favorite/favorite/cancle',
			data:'favorite_id='+id,
			async: false,
			error: function(request) {
				showMessage('请先登录',1500);
			},
			success: function(data) {
				//alert(data);
				if(data.errno==0){
					/*
					art.dialog({
						time: 1.5,
						content: '取消成功'
					});
					*/
					showMessage('取消成功',1500);
					$('#favorite').removeClass('sc');
					$('#favorite').attr('class','ysc');
					$('#favorite').attr('onclick','favorite(1,'+product_id+')');
					return;
				}else{
					alert(data.errmsg);
				}
			}
		});
	}
}