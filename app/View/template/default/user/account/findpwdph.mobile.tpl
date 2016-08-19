{include file='public/fr/header.mobile.tpl'}
{if empty($mobile)}
<script>	 
	 window.location.href=URL+'/user/account/findpwd'; 
</script> 
{/if}

 
 <div class="menu">
   <a class="back" href="/user/account/findpwd"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">找回密码</div>
   <a href="/user/account/register" class="webtxt">注册</a>
</div>
<div class="info minh400">
   <div class="mm_zhaohui">验证码已发送，请注意查收！</div>
   <div class="mm_sjh">手机号：{$mobile}</div>
    <input type="hidden" name="mobile" id="mobile" value="{$mobile}" />
   <div class="bbtn"><input type="submit" num="60" value="60秒后重新发送" id="show_msg" class="w_btn" ></div>
   <div class="ptxt mgt15"><input type="text" class="p_txt" placeholder="验证码" value="" ></div>
	 <div class="btxt mgt15"> 
	 <input name="code" type="password" id="pwd2"  class="p_txt"   /> 
 
    </div>
    <div class="bbtn mgt15"><input type="submit" value="确认修改并登录" class="i_btn"></div>
</div>

 
<script type="text/javascript">  

$(function(){ 
	 time1 = setInterval(GetRTime,1000);	 
	 
	 $('.i_btn').click(function(){ 
 	   if($(".p_txt").val() ==''){ 
			showMessage('请输入验证码',2000);
			return false;	
	   }
	   if($("#pwd2").val() ==''){
	    
			showMessage('请输入密码',2000);
			return false;	
	   }	   
	 
	    
       var user = {
            account:{$mobile},
            code:$(".p_txt").val(),
            password:$("#pwd2").val()
        };
        $.ajax({
            url:URL+'user/account/password',
            data:user,
            type:'post',
            dataType:'json',
            success:function(data){ 
				if(data.errno=='0'){  
					showMessage(data.notice,4000); 
					window.location.href=URL+'/user/account/login';
				}else{
				 
					showMessage(data.errmsg,2000); 
				}
            }
             
        })
 
	 	
	 });
 
});        
/*
/ 短信倒计时
*/
function GetRTime(){
  var s = parseInt($('#show_msg').val()); 
  if(s==1){
	$('#show_msg').val('重新发送');
	$('#show_msg').attr('num',60);
	clearInterval(time1);
	$('#show_msg').click(function(){
	  sentmsm(); 
	});
  }else{
	  //alert(s);
	$('#show_msg').val((s-1)+'秒');
	$('#show_msg').attr('num',s-1);
  }
}	   
          
/*
/ 手机短信发送
*/
var time1 = '';
function sentmsm(){
	var mobile = $('#mobile').val();
	if(mobile.length!=11){
		/*
		art.dialog({
			id:'reg',
			time: 2,
			content: '手机格式错误'
		});
		*/
		showMessage('手机格式错误',2000);
		return false;
	}
	$.ajax({
		dataType:'json',
		type: "POST",
		data:'account='+mobile,
		url:URL+'user/account/findpwdph',
		error: function(request) {
			//alert("Connection error");
			showMessage('系统繁忙',2000);
		},
		success: function(data) {
		  //alert(data.errmsg);return;
		  if(data.errno==1){
			/*
			art.dialog({
				time: 2,
				content: data.errmsg
			});
			*/
			showMessage(data.errmsg,2000);
		  }else{
			$('#show_msg').val('60秒');
			$('#show_msg').unbind("click");
			time1 = setInterval(GetRTime,1000);
		  }
		}
	});							
}
     
	                    
		var tx = document.getElementById("tx2"), pwd = document.getElementById("pwd2"); 
			tx.onfocus = function(){ 
			if(this.value != "新密码") return; 
			this.style.display = "none"; 
			pwd.style.display = ""; 
			pwd.value = ""; 
			    pwd.focus(); 
		} 
		pwd.onblur = function(){ 
			if(this.value != "") return; 
			this.style.display = "none"; 
			tx.style.display = ""; 
			tx.value = "新密码"; 
		}  
</script>
 
{include file='public/fr/footer.mobile.tpl'}