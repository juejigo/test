{include file='public/fr/header.mobile.tpl'}

<!--<FORM   method="post"   ACTION="/user/account/findpwd" id="login"  > -->
	
<FORM METHOD=POST ACTION="" id="binding" onsubmit="return false;">
<div class="menu">
   <a class="back" href="//user/member"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">绑定手机</div>
</div>
<div class="info minh400">
   <div class="ptxt"><input type="text" name="account" id="mobile" class="p_txt" placeholder="请输入手机号码"></div>

   <div class="btxt"><input name="" type="text" value="6-16位密码" class="p_txt" id="tx" /> 
<input name="password" type="password" id="pwd" class="p_txt" style="display:none;" /> 

<script type="text/javascript" src="{$smarty.const.URL_WEB}webapp/js/register.js"></script>

<script type="text/javascript"> 
var tx = document.getElementById("tx"), pwd = document.getElementById("pwd"); 
tx.onfocus = function(){ 
if(this.value != "6-16位密码") return; 
this.style.display = "none"; 
pwd.style.display = ""; 
pwd.value = ""; 
pwd.focus(); 
} 
pwd.onblur = function(){ 
if(this.value != "") return; 
this.style.display = "none"; 
tx.style.display = ""; 
tx.value = "6-16位密码"; 
} 

function fleshVerify(){
//重载验证码
	var timenow = new Date().getTime();
	$('#verifyImg').attr('src','/Utility/captcha?time='+timenow);
}
</script> </div>

   <div class="bbtxt">
       <div class="stxt"><input type="text" class="s_txt" value="请输入手机短信验证码" onFocus="if(value =='请输入手机短信验证码'){ value =''}"onblur="if(value ==''){ value='请输入手机短信验证码' }" name="code" id="code"></div>
       <div class="sstxt" id="show_msg" num="60">发送短信验证码</div>
   </div>   

   <div class="bbtn"><input type="submit" value="绑定" class="i_btn" onclick="send('binding','/user/account/bindingmb');"></div>
   
</div> 
</FORM>
 <script> 
/*
$(document).ready(function(){
  $('#show_msg').click(function(){
	 sentmsm();
  });

});	

var time1 = '';
function sentmsm(){
	var mobile = $('#mobile').val();
	if(mobile.length!=11){
		showMessage('手机格式错误',2000);
		return false;
	}
	$.ajax({
		dataType:'json',
		type: "POST",
		data:'mobile='+mobile,
		url:URL+'user/account/bindingcode',
		error: function(request) {
			//alert("Connection error");
			showMessage('系统繁忙',2000);
		},
		success: function(data) {
		  //alert(data.errmsg);return;
		  if(data.errno==1){
			showMessage(data.errmsg,2000);
		  }else{
			  alert(data.errno);
			$('#show_msg').text('60秒');
			$('#show_msg').unbind("click");
			time1 = setInterval(GetRTime,1000);
		  }
		}
	});							
}

function GetRTime(){
  var s = parseInt($('#show_msg').attr('num'));
  if(s==1){
	$('#show_msg').text('重新发送');
	$('#show_msg').attr('num',60);
	clearInterval(time1);
	$('#show_msg').click(function(){
	   sentmsm();
	});
  }else{
	  //alert(s);
	$('#show_msg').text((s-1)+'秒');
	$('#show_msg').attr('num',s-1);
  }
}
*/
</script>
 
{include file='public/fr/footer.mobile.tpl'}