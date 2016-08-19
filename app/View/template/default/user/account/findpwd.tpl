{include file='public/fr/header.mobile.tpl'}

<!--<FORM   method="post"   ACTION="/user/account/findpwd" id="login"  > -->
	
	
<div class="menu">
   <a class="back" href="/user/account/login"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">找回密码</div>
   <a href="/user/account/register" class="webtxt">注册</a>
</div>
<div class="info minh400">
   <div class="ptxt"><input type="text" name="account" class="p_txt" placeholder="请输入手机号码"></div>
   
   <div class="bbtn"><input type="button" value="下一步" id="login_do" class="w_btn"></div>
   
</div> 
 
<!--</FORM>-->
 <script> 
	
	$('#login_do').click(
		function(){
			var account=$('.p_txt').val();
			if(account == ''){
				return false;
			}  
			$.ajax({
				
				cache: true,
				dataType:'json',
				type: "POST",
				url:URL+'user/account/findpwdph',
				data:'account='+account,
				async: false,
				error: function(request) { 
					
					showMessage('Connection error',2000);
					return false;					
				},
				success: function(data) {
					 
					if(data.errno==0){
						
					     window.location.href=URL+'user/account/findpwdph'; 
					     
					 
					}else{ 
						showMessage(data.errmsg,2000);
						return false;						
					}
				}
			});		 
		 
		}
	);
	
	

 
	
 	
 </script>
 
 
 
 
 
 
 
 
{include file='public/fr/footer.mobile.tpl'}