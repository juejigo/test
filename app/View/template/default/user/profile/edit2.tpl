{include file='public/fr/header.mobile.tpl'}
<script type="text/javascript" src="{$smarty.const.URL_WEB}webapp/js/ajaxfileupload.js"></script>
<script type="text/javascript">
   function opena(){
       document.getElementById('xingbie').style.display='block';
	   document.getElementById('fade').style.display='block';
    }
	function openb(){
       document.getElementById('nianlin').style.display='block';
	   document.getElementById('fade').style.display='block';
    }
   function closea(){
       document.getElementById('xingbie').style.display='none';
	   document.getElementById('fade').style.display='none';
    }
	function closeb(){
       document.getElementById('nianlin').style.display='none';
	   document.getElementById('fade').style.display='none';
    }
</script>
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
<FORM METHOD=POST ACTION="">
<div class="viewport">
<div id="xingbie" class="mem_xuanze"> 
        <div class="mem_ctt">性别</div>
        <div class="mem_li">
           <li><a href="#" class="cur">男</a></li>
           <li><a href="#">女</a></li>
        </div>
        <div class="qx_btn"><input type="button" value="取消" onclick="closea()" class="close"></div>
</div> 
<div id="nianlin" class="mem_xuanze"> 
        <div class="mem_ctt">性别</div>
        <div class="mem_li2">
           <li><a href="#">2013</a></li>
           <li><a href="#" class="cur">2014</a></li>
           <li><a href="#">2015</a></li>
        </div>
        <div class="qx_btn2"><input type="button" value="确定" onclick="closeb()" class="sure"><input type="button" value="取消" onclick="closeb()" class="close"></div>
</div> 
<div id="fade" class="black_overlay"></div> 

<div class="menu">
   <a class="back" href="{$smarty.const.DOMAIN}user/member"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">个人资料</div>
</div>
<div class="menber">

	<input type="file" id="avatar" name="avatar" onchange="ImgPreview(this,'show1')" style="display:none">

    <div class="mem_tp1"><a href="#">头像<span id="show1"><img src="{$profile.avatar}" onclick="$('#avatar').click();"></span></a>
	</div>
    <div class="mem_tp2 mgt15"><a href="#">账号<span>{$profile.member_name}</span></a></div>
    <div class="mem_tp3"><a href="javascript:void(0);" onClick="opena()">性别<span>未选择</span></a></div>
    <div class="mem_tp3"><a href="javascript:void(0);" onClick="openb()">年龄<span>未选择</span></a></div>
    <div class="mem_tp2 mgt15"><a href="#">会员等级<span>白金会员</span></a></div>
</div>
</FORM>
{include file='public/fr/footer.mobile.tpl'}