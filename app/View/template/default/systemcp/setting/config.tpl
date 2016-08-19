 {include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
 <link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen"> 
 <link href="{$smarty.const.URL_WEB}css/admincp/index/style.css" rel="stylesheet" media="screen">
 <script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/jquery-1.8.3.min.js" charset="UTF-8"></script>
 <script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
 <script type="text/javascript" src="{$smarty.const.URL_WEB}js/public/region.js" charset="UTF-8" p="{$province_id}" c1="{$city_id}" c2="{$county_id}"  id="js_region">	 </script>
	<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">基本设置 <small>基本信息设置</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/systemcp/setting/config">设置</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/systemcp/setting/config">系统设置</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/systemcp/setting/config">基本设置</a></li>
				</ul>
			</div>
			<!--表单-->
			<div class="row">
				<div class="col-md-12">
					<form action="/systemcp/setting/config" class="form-horizontal form-row-seperated" method="post">
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-edit"></i>内容设置
								</div>
								<div class="pull-right">
									<button type="reset" class="btn default"><i class="fa fa-refresh"></i> 重置</button>
									<button type="submit" class="btn green"><i class="fa fa-check-circle"></i> 保存</button>
								</div>
							</div>
						</div>
						<div class="col-md-12">
						{foreach $config as $con}
							{if $con.level == 1}
							<div class="s_title">
								<span>{$con.field_name}</span>
							</div>
							{else if $con.input_type == 0}
							<div class="s_title" style="margin-left:{($con.level-1)*40}px;width:{100-($con.level-1)*4}%;">
								<span style="color:black">->{$con.field_name}</span>
							</div>
							{else if $con.input_type == 2}
							<div class="form-group">
								<label class="col-md-3 control-label">{$con.field_name}</label>
								<div class="col-md-5">
									<div class="radio-list">
										<label class="radio-inline">
										  <input type="radio" {if $con.allow_modify === '0'}disabled="disabled"{/if} name="id[{$con.id}]" {if $con.value == 1} checked = "checked" {/if} value="1"> 是
										</label>
										<label class="radio-inline">
										  <input type="radio" {if $con.allow_modify === '0'}disabled="disabled"{/if} name="id[{$con.id}]" {if $con.value === '0'} checked = "checked" {/if} value="0"> 否
										</label>
									</div>
								</div>
							</div>
							{else if $con.input_type == 1}
							<div class="form-group">
								<label class="control-label col-md-3">{$con.field_name}</label>
								<div class="col-md-5"><input type="text" {if $con.allow_modify === '0'}readonly="readonly"{/if} class="form-control" name="id[{$con.id}]" value="{$con.value}" placeholder=""></div>
							</div>
							{else if $con.input_type == 3}
							<div class="form-group">
								<label class="control-label col-md-3">{$con.field_name}</label>
								<div class="col-md-5">
									<select class="form-control" name="id[{$con.id}]" {if $con.allow_modify === '0'}disabled="disabled"{/if}>
										{foreach $con.options as $option}
										<option value="{$option.value}"{if $con.value == $option.value}selected="selected"{/if}>{$option.name}</option>
										{/foreach}
									</select>
								</div>
							</div>
							{else if $con.input_type == 4}
							<div class="form-group">
								<label class="control-label col-md-3">{$con.field_name}</label>
								<div class="col-md-5"><input data-toggle="modal" {if $con.allow_modify === '0'}disabled="disabled"{/if} data-target="#image_uploader" name="id[{$con.id}]" class="form-control" value="{$con.value}" placeholder="点击上传图片"></div>
							</div>
							{/if}
						{/foreach}
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!--content-wrapper /-->
</div>
<!-- container /-->
{include file='public/admincp/footer.tpl'}
<script>
{foreach $config as $con}
{if $con.input_type == 4 && $con.allow_modify == 1}
$(function() {
	$('input[name="id[{$con.id}]"]').click(function()
	{
		imageUploader.show('brand',callback{$con.id});
	})    
});
function callback{$con.id}(responseObject)
{
	json = $.parseJSON(responseObject.response);
	if (json.flag == 'error')
	{
		showMessage(json.msg);
	}
	else if (json.flag == 'success')
	{
		$('input[name="id[{$con.id}]"]').val(json.url);
	}
}
{/if}
{/foreach}
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
