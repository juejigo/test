{include file='public/fr/header.mobile.tpl'}
<div class="menu">
   <a class="back" href="{if $from_url}{$from_url|resetUrl:'consignee_id'}{else}/user/member{/if}"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">地址管理</div>
   <a class="btn_tj" href="/consignee/consignee/add{if $from_url}?from_url={$from_url}{/if}"><img src="{$smarty.const.URL_WEB}webapp/images/btn_tj.png"></a>
</div>
<script>
var from_url = "{$from_url|resetUrl:'consignee_id'}";
//默认选择
function choice(id){
	window.location.href = from_url+'&consignee_id='+id;
}

//设置默认
function setdefault(id){
	$.ajax({
		cache: true,
		dataType:'json',
		type: "POST",
		url:URL+'consignee/consignee/setdefault',
		data:'id='+id,
		async: false,
		error: function(request) {
			alert("Connection error");
		},
		success: function(data) {
			if(data.errno==0){
				art.dialog({
					time: 1,
					content: '设置成功'
				});
				setTimeout(function(){
					 location.reload();
				},1000);
				return;
			}else{
				alert(data.errmsg);
			}
		}
	});

}
//编辑
function edit(id){
  window.location.href = URL+'consignee/consignee/edit?&id='+id+'{if $from_url}&from_url={$from_url|urlencode}{/if}';
}

//删除
function del(id,o){
	$.ajax({
		cache: true,
		dataType:'json',
		type: "POST",
		url:URL+'consignee/consignee/delete',
		data:'id='+id,
		async: false,
		error: function(request) {
			alert("Connection error");
		},
		success: function(data) {
			if(data.errno==0){
				$('#c'+id).remove();
				return;
			}else{
				alert(data.errmsg);
			}
		}
	});
}
</script>
<div class="menber">
   {foreach $consignee_list as $i => $consignee}
   <div class="dz_box {if $consignee.default==1}dz_cur{/if}" id="c{$consignee.id}">
      <p class="sg"><em>{$consignee.consignee}</em>{$consignee.mobile}</p>
      <p>{$consignee.region_path} {$consignee.address}</p>
	  {if $from_url}
      <p class="mgt15"><input type="button" value="修改并选择" class="dz_xg"  onclick="edit({$consignee.id});"><input type="button" value="选择" class="dz_xz" onclick="choice({$consignee.id});"></p>
	  {else}
      <p class="mgt15"><input type="button" value="修改" class="dz_xg"  onclick="edit({$consignee.id});"><input type="button" value="默认" class="dz_xz" onclick="setdefault({$consignee.id});">  
	  <input type="button" value="删除" class="dz_sc" onclick="del({$consignee.id});"></p>
	  {/if}

   </div>
   {/foreach}
</div>
{include file='public/fr/footer.mobile.tpl'}