{include file='public/fr/header.mobile.tpl'}

<div class="menu">
   <a class="back" href="/user/member"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">我的收藏</div>
</div>
<div class="menber">

    	
    {foreach $favorite_list as $i => $favorite} 
     <div class="memsc_box" id="rm{$favorite['id']}">
       <div class="scbox_info"><a href="/product/product/detail?id={$favorite['dataid']}"><span><img src="{$favorite['image']}"></span><p><i>{$favorite['title']}</i><em>{$favorite['price']}</em></p></a></div>
       <div class="btn_del">
       	
       	<a href="javascript:void(0)" onclick="del({$favorite['id']})";>删除</a>
       	 
       </div>
       </div> 
    
    {/foreach}  

 
</div>
<script> 
	
//删除
function del(id){
 
	URL="/";
	$.ajax({
		
		cache: true,
		dataType:'json',
		type: "POST",
		url:URL+'favorite/favorite/cancle',
		data:'favorite_id='+id,
		async: false,
		error: function(request) {
			alert("Connection error");
		},
		success: function(data) {
			 
			if(data.errno==0){
				$('#rm'+id).remove();
				return;
			}else{
				alert(data.errmsg);
			}
		}
	});
}
	
</script> 



{include file='public/fr/footer.mobile.tpl'}