{include file="public/vote/header.tpl"}
<body class="pb_80 b_bc">
<!--报名标题-->	
<section class="title">报名处</section>
<!--报名标题 /-->
<section class="form_view">
	<div class="input_box req">
		<label>真实姓名：</label>
		<div class="padl60">
            <input type="text" id="realname" placeholder="请输入您的真实姓名">
        </div>
	</div>
	<div class="input_box req">
		<label>手机号码：</label>
		<div class="padl60">
            <input type="tel" id="phone" placeholder="请输入您的手机号码">
        </div>
	</div>
	<form action="" method="post" id="uploadImg" enctype="multipart/form-data">
	<div class="input_box req">
		<label>个人照片：</label>
		<div class="padl60">
            <div class="up_img">
            	<input type="file" id="upImg" name="upImg" class="file" accept="image/*">
            	<input type="hidden" id="photo" value="">	
            	<img src="{$smarty.const.URL_WEB}webapp/images/plus.jpg">	
            </div>
            <font color="red">请上传真实照片，否则不予通过</font>
        </div>
	</div>
	</form>
	<div class="input_box req">
		<label>参赛宣言：</label>
		<div class="padl60">
            <textarea id="manifesto" placeholder=""></textarea>
        </div>
	</div>
	<div class="input_box">
		<div style="text-align:center;">
            <button class="form_btn" onclick="signUp()"><span>报名</span></button>
        </div>
	</div>
</section>
	
</form>
{include file="public/vote/footer.tpl"}
</body>
</html>
<script src="{$smarty.const.URL_WEB}webapp/js/vender/jquery/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_WEB}webapp/js/vender/jquery/jquery.form.js"></script>
<script src="{$smarty.const.URL_WEB}webapp/js/cj.js"></script>
<script src="{$smarty.const.URL_WEB}webapp/js/signUp.js"></script>