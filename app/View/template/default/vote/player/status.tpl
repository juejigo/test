{include file="public/vote/header.tpl"}
<body class="pb_80 b_bc">
<!--已报名，报名进度-->
<section class="schedule_box">
	<div class="info_box">
		<div class="tx_img"><img src="{$status.thumbimage}" width="100%" /></div>
		<div class="info">
			<h2>{$status.name}</h2>
			<p>{$status.declaration}</p>
		</div>
	</div>
	<div class="schedule">
	{if $status.status == 0}
		<img src="{$smarty.const.URL_WEB}webapp/images/shz.png" alt="审核中" width="100%"/>
	{/if}
	{if $status.status == 1 || $status.status == -1}	
		<img src="{$smarty.const.URL_WEB}webapp/images/shjg.png" alt="审核结果" width="100%"/>
	{/if}
	</div>
</section>
<div class="blank"></div>
<section class="schedule_box">
	{if $status.status == -1}<div class="title">审核失败原因<span onclick="againShow()">重新报名</span></div>
	<div class="reason">{$status.nopass}</div>{/if}
	{if $status.status == 1}<div class="title">审核成功</div>{/if}
	{if $status.status == 0}<div class="title">审核中</div>{/if}
</section>
<!--已报名，报名进度 /-->
{include file="public/vote/footer.tpl"}
{if $status.status == -1}
<!--重新报名-->
<div class="sign_up_again">
	<section class="title">重新报名</section>
	<section class="form_view">
		<div class="input_box req">
			<label>真实姓名：</label>
			<div class="padl60">
	            <input type="text" id="realname" placeholder="请输入您的真实姓名" value="{$status.name}">
	        </div>
		</div>
		<div class="input_box req">
			<label>手机号码：</label>
			<div class="padl60">
	            <input type="tel" id="phone" placeholder="请输入您的手机号码" value="{$status.phone}">
	        </div>
		</div>
		<form action="" method="post" id="uploadImg" enctype="multipart/form-data">
		<div class="input_box req">
			<label>个人照片：</label>
			<div class="padl60">
	            <div class="up_img">
	            	<input type="file" id="upImg" class="file" name="upImg" accept="image/*" runat="server">
	            	<input type="hidden" id="photo" value="{$status.image}">	
	            	<img src="{$status.thumbimage}">	
	            </div>
	        </div>
		</div>
		</form>
		<div class="input_box req">
			<label>参赛宣言：</label>
			<div class="padl60">
	            <textarea id="manifesto" placeholder="请输入自我介绍，个人详情，拉票宣言等...">{$status.declaration}</textarea>
	        </div>
		</div>
		<div class="input_box">
			<label>&nbsp;</label>
			<div class="padl60">
	            <button class="form_btn" onclick="signUp()"><span>报名</span></button>
	        </div>
		</div>
	</section>
</div>
<!--重新报名 /-->
{/if}
</body>
</html>
<script src="{$smarty.const.URL_WEB}webapp/js/vender/jquery/jquery-1.11.1.js"></script>
<script src="{$smarty.const.URL_WEB}webapp/js/vender/jquery/jquery.form.js"></script>
<script src="{$smarty.const.URL_WEB}webapp/js/cj.js"></script>
<script src="{$smarty.const.URL_WEB}webapp/js/signUp.js"></script>