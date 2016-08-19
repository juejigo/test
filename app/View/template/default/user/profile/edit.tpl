{include file='public/fr/header.mobile.tpl'}
<script type="text/javascript">
function ImgPreview(file,div_id,img_id){
  var div_id = div_id ? div_id : 'preview';
  var img_id = img_id ? img_id : 'imghead';
  var MAXWIDTH  = 100;
  var MAXHEIGHT = 100;
  var div = document.getElementById(div_id);
  if (file.files && file.files[0])
  {
    div.innerHTML = '<img id=imghead onclick="$(\'#avatar\').click();">';
    var img = document.getElementById(img_id);
    img.onload = function(){
      var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
      //img.width = rect.width;
      //img.height = rect.height;
      //img.style.marginLeft = rect.left+'px';
      //img.style.marginTop = rect.top+'px';
    }
    var reader = new FileReader();
    reader.onload = function(evt){
		img.src = evt.target.result;
	}
    reader.readAsDataURL(file.files[0]);
  }
  else
  {
    var sFilter='filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="';
    file.select();
    var src = document.selection.createRange().text;
    div.innerHTML = '<img id=imghead onclick="$(\'#avatar\').click();">';
    var img = document.getElementById('imghead');
    img.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = src;
    var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
    status =('rect:'+rect.top+','+rect.left+','+rect.width+','+rect.height);
    div.innerHTML = "<div id=divhead style='width:"+rect.width+"px;height:"+rect.height+"px;margin-top:"+rect.top+"px;margin-left:"+rect.left+"px;"+sFilter+src+"\"'></div>";
  }
}

function clacImgZoomParam( maxWidth, maxHeight, width, height ){
    var param = {
		top:0, left:0, width:width, height:height
	};
    if( width>maxWidth || height>maxHeight )
    {
        rateWidth = width / maxWidth;
        rateHeight = height / maxHeight;
        if( rateWidth > rateHeight )
        {
            param.width =  maxWidth;
            param.height = Math.round(height / rateWidth);
        }else
        {
            param.width = Math.round(width / rateHeight);
            param.height = maxHeight;
        }
    }
    param.left = Math.round((maxWidth - param.width) / 2);
    param.top = Math.round((maxHeight - param.height) / 2);
    return param;
}
</script>
<FORM METHOD=POST ACTION="/user/profile/edit" enctype="multipart/form-data">
<div class="menu">
   <a class="back" href="/user/member"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">个人资料</div>
</div>
<div class="menber">
	<input type="file" id="avatar" name="image" onchange="ImgPreview(this,'show1')" style="display:none">
	
    <div class="mem_tp1">
	<a href="javascript:;">头像<span id="show1"><img src="{$profile.avatar}" onclick="$('#avatar').click();"></span></span></a></div>
    <div class="mem_tp2 mgt15"><a href="javascript:;">账号<span><INPUT TYPE="text" NAME="member_name" value="{$profile.member_name}" style="width:40px;"></span></a>
	</div>

    <div class="mem_tp3">
	<p>性别<span>
		<select name="sex">
			<option {if $profile.sex==1}selected{/if} value="1">男</option>
			<option {if $profile.sex==0}selected{/if} value="0">女</option>
		</select>
	</span></p>
	</div>

    <div class="mem_tp2 mgt15"><a href="javascript:;">会员等级<span>{$profile.group_name}</span></a></div>
    <div class="pj_btn mgt15"><input type="submit" class="i_btn" value="保存编辑"></div>
</div>
</FORM>
{include file='public/fr/footer.mobile.tpl'}