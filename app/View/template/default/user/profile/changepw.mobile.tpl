{include file='public/fr/header.mobile.tpl'}
<div class="menu">
   <a class="back" href="/user/member/safety"><img src="{$smarty.const.URL_WEB}webapp/images/menu_back.png"></a>
   <div class="tit">修改登录密码</div>
</div>
<div class="menber">
    <div class="men_list2">
	<FORM METHOD=POST ACTION="/user/profile/password" id="changepw" onsubmit="return false;">
       <ul>
          <li><input name="" type="text" value="原密码" class="m_mmnew" id="mm1" /> 
              <input name="ori_password" type="password" id="pp1"  class="m_mmnew" style="display:none;" /> 
          </li>
          <li><input name="" type="text" value="新密码" class="m_mmnew" id="mm2" /> 
              <input name="password" type="password" id="pp2"  class="m_mmnew" style="display:none;" /> 
          </li>
          <li><input name="" type="text" value="确认新密码" class="m_mmnew" id="mm3" /> 
              <input name="password2" type="password" id="pp3"  class="m_mmnew" style="display:none;" /> 
          </li>
       </ul>
	</FORM>
       <script type="text/javascript"> 
                    var ymm = document.getElementById("mm1"), ypd = document.getElementById("pp1"); 
					var xmm = document.getElementById("mm2"), xpd = document.getElementById("pp2");
					var cmm = document.getElementById("mm3"), cpd = document.getElementById("pp3");
                    ymm.onfocus = function(){ 
                    if(this.value != "原密码") return; 
                    this.style.display = "none"; 
                    ypd.style.display = ""; 
                    ypd.value = ""; 
                    ypd.focus(); 
                    } 
                    ypd.onblur = function(){ 
                    if(this.value != "") return; 
                    this.style.display = "none"; 
                    ymm.style.display = ""; 
                    ymm.value = "原密码"; 
                    } 
					xmm.onfocus = function(){ 
                    if(this.value != "新密码") return; 
                    this.style.display = "none"; 
                    xpd.style.display = ""; 
                    xpd.value = ""; 
                    xpd.focus(); 
                    } 
                    xpd.onblur = function(){ 
                    if(this.value != "") return; 
                    this.style.display = "none"; 
                    xmm.style.display = ""; 
                    xmm.value = "新密码"; 
                    } 
					cmm.onfocus = function(){ 
                    if(this.value != "确认新密码") return; 
                    this.style.display = "none"; 
                    cpd.style.display = ""; 
                    cpd.value = ""; 
                    cpd.focus(); 
                    } 
                    cpd.onblur = function(){ 
                    if(this.value != "") return; 
                    this.style.display = "none"; 
                    cmm.style.display = ""; 
                    cmm.value = "确认新密码"; 
                    } 

					function submit(){
					    //alert($('#pp2').val()+'/'+$('#pp3').val());
						if($('#pp2').val()!=$('#pp3').val()){
							alert('2次输入密码不一样');
						}else{
							send('changepw','/user/profile/password');
						}
					}
                    </script>
    </div>
    <div class="sbtn"><input type="submit" value="确认" class="i_btn" onclick="submit();"></div>
</div>
{include file='public/fr/footer.mobile.tpl'}