{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
<link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css"/>
		
	<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">编辑刮刮卡活动 <small>编辑一个已存在的刮刮卡活动</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/scrathcp/scrath/list">刮刮卡列表</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/scrathcp/scrath/edit">编辑</a></li>
				</ul>
			</div>
			<!-- 错误提醒 -->
				{if $error->hasError()}
				{foreach $error->getAll() as $e}
				<div class="alert alert-error">
						<button type="button" class="close">&times;</button>
						<strong>错误！</strong> {array_shift($e)}
				</div>
				{/foreach}
				{/if}
			<!-- 错误提醒 /-->
			<!--表单-->
			<div class="row">
				<div class="col-md-12">
					<form action="/scrathcp/scrath/edit?id={$params.id}" class="form-horizontal form-row-seperated" method="post">
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-edit"></i>内容设置
								</div>
								<div class="pull-right">
									<a href="/scrathcp/scrath/list" class="btn default"><i class="fa fa-angle-left"></i> 返回</a>
									<button type="reset" class="btn default"><i class="fa fa-refresh"></i> 重置</button>
									<button type="submit" class="btn green"><i class="fa fa-check-circle"></i> 保存</button>
								</div>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="tab-content">
								<div class="form-body">
									<div class="form-group">
										<label class="control-label col-md-2">名称 <span class="required" aria-required="true">*</span></label>
										<div class="col-md-4"><input type="text" class="form-control" name="scrath_name" value ="{$scrathInfo.scrath_name}"placeholder="请输入活动名称，不超过20个字"></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">上传横幅 <span class="required" aria-required="true">*</span></label>
										<div class="col-md-4">
										<input data-toggle="modal" data-target="#image_uploader" name="info_image" value="{$scrathInfo.info_image}"  class="form-control" placeholder="点击上传图片"></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">奖项设置 <span class="required" aria-required="true">*</span></label>
										<div class="col-md-10">
											<div class="dataTables_wrapper no-footer" id="sample_1_wrapper">
												<div class="table-scrollable">
													<table class="table table-bordered table-hover dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
														<thead>
															<tr role="row">
																<th width="80">序号</th>
																<th width="200">奖项等级</th>
																<th width="200">图片</th>
																<th width="100">图片预览</th>
																<th width="200">奖项名称</th>
																<th width="100">总数量</th>
																<th width="100">剩余数量</th>
																<th>操作</th>
															</tr>
														</thead>
														<tbody id="tbody">
														{foreach $scrathProductList as $scrathProduct }
																<tr data-id='{$scrathProduct.id}' class="gradeX odd" role="row">
																	<td>{$scrathProduct.id}<input type="hidden" name="status[]" value="{$scrathProduct.status}"></td>
																	<input type="hidden" name="product_id[]" value="{$scrathProduct.id}">
																	<td><input type="text" class="form-control" name="product_level[]" value="{$scrathProduct.product_level}"></td>
																	<td><input type="text" class="form-control up" name="image[]" value="{$scrathProduct.image}"></td>
																	<td><img src="{$scrathProduct.image}" width="90" height="90"></td>
																	<td><input type="text" class="form-control" name="product_name[]" value="{$scrathProduct.product_name}"></td>
																	<td><input type="text" class="form-control" name="stock[]" value="{$scrathProduct.total_num}"></td>
																	<td><input type="text" class="form-control" name="surplus[]" value="{$scrathProduct.stock}"></td>
																	<td><a href="javascript:;" class="del_tr btn red">删除</a></td>
																</tr>
														{/foreach}
														</tbody>
													</table>
												</div>
											</div>
											<a href="javascript:;" class="btn btn-default" id="addTr"><i class="fa fa-plus"></i> 添加奖项</a>
										</div>
									</div>
								
									
																		<div class="form-group">
										<label class="control-label col-md-2">活动时间 <span class="required" aria-required="true">*</span></label>
										<div class="col-md-5">
											<div class="input-group">
												<input type="text" name="start_time" class="form-control form_datetime" value="{$scrathInfo.start_time}" placeholder="开始时间">
												<span class="input-group-addon">至</span>
												<input type="text" name="end_time" class="form-control form_datetime" value="{$scrathInfo.end_time}" placeholder="结束时间">
											</div>
										</div>
									</div>
									
									
							<div role="tabpanel" class="tab-pane" id="costPane">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">活动介绍：</label>
                                    <div class="col-md-6">
                                        <textarea class="form-control ke" name="content">{html content=$scrathInfo.content}</textarea>
                                    </div>
                                </div>
                            </div>
                                 <div class="form-group">
                                    	<label class="col-md-2 control-label">领取方式：</label>
                                        <div class="col-md-6">
                                            <textarea class="form-control" name="info" placeholder="领取方式" rows="4">{$scrathInfo.info}</textarea>
                                        </div>
                                  </div>
									<div class="form-group">
										<label class="control-label col-md-2">每个用户抽奖次数 <span class="required" aria-required="true">*</span></label>
										<div class="col-md-4">
											<input type="text" class="form-control" name="lottery_num" value="{$scrathInfo.lottery_num}" id="lottery_num">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">抽奖金额 <span class="required" aria-required="true">*</span></label>
										<div class="col-md-4">
											<input type="text" class="form-control" name="draw_amount" value="{$scrathInfo.draw_amount}" id="draw_amount">
											<span class="help-block">每支付 <span class="text-danger">{$scrathInfo.draw_amount}</span> 元，获得1次抽奖机会</span>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">奖品总数 <span class="required" aria-required="true">*</span></label>
										<div class="col-md-4">
											<input type="text" class="form-control" name="total_num" value="{$scrathInfo.total_num}" id="total_num">
										</div>
									</div>
									<input type="hidden" name="scrath_id" value="{$params.id}" />
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<!--表单 /-->
		</div>
	</div>
	<!--content-wrapper /-->
</div>
{include file='public/admincp/footer.tpl'}
<script src="{$smarty.const.URL_JS}lib/kindeditor/kindeditor-all-min.js"></script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/themes/default/default.css"></script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/lang/zh_CN.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- 上传图片 /-->
{literal}
<script>
$(function() {    
    Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
	$(".date-picker").datepicker({ 
		rtl: Metronic.isRTL(),
        autoclose: true
    });
	$('input[name="info_image"]').click(function()
			{
				imageUploader.show('scrath',callback);
			})

});

function callback(responseObject)
{
	json = $.parseJSON(responseObject.response);
	
	if (json.flag == 'error')
	{
		showMessage(json.msg);
	}
	else if (json.flag == 'success')
	{
		$('input[name="info_image"]').val(json.url);
	}
}

KindEditor.ready(function(K) {
    window.editor = K.create('.ke',{
        uploadJson:'/imageuc/kindeditor/upload?',
        resizeType :1,
        width:'100%',
        height:'300px',
        allowPreviewEmoticons : true,
        allowImageUpload : true,
        urlType:'domain'
    });	
});

$("#draw_amount").keyup(function(){
	$(this).next().find("span").text($(this).val());
})
//增加TR
var tbody=$("#tbody");
var i=1;
$("#addTr").click(function(){
	var str='';
	str+='<tr data-id='+i+' class="gradeX odd" role="row">';
	str+='<td>新<input type="hidden" name="status[]" value="0"></td><input type="hidden" name="product_id[]" value="">';
	str+='<td><input type="text" class="form-control" name="product_level[]"></td>';
	str+='<td><input type="text" class="form-control up" name="image[]"></td>';
	str+='<td><img src="" width="90" height="90"></td>';
	str+='<td><input type="text" class="form-control" name="product_name[]"></td>';
	str+='<td><input type="text" class="form-control" name="stock[]"></td>';
	str+='<td><input type="text" class="form-control" name="surplus[]"></td>';
	str+='<td><a href="javascript:;" class="del_tr btn red">删除</a></td>';
	str+='</tr>';
	tbody.append(str);
	i++;
})
//上传图片回调函数
$(document).on('click',"input.up",function(){
	var _this=$(this);
	$('#image_uploader').modal('show');
	imageUploader.show('position',callback);
	function callback(responseObject)
	{	
		var imgDom=_this.parent().next().find("img");
		json = $.parseJSON(responseObject.response);
		if (json.flag == 'error')
		{
			showMessage(json.msg);
		}
		else if (json.flag == 'success')
		{
			_this.val(json.url);
			imgDom.attr("src",json.url);
		}
	}
})
//删除tr
$(document).on("click",'.del_tr',function(){
	var tr = $(this).closest('tr');
	if (confirm('确定删除？')){
		if(tr.find('input[name^=product_id]').val() == '')
		{
			tr.remove();
		}else
		{
			tr.find('input[name^=status]').val(-1);
			tr.css("display","none");
		} 
	}
})
//绑定上下架时间
$(".form_datetime").datetimepicker({
    language: 'zh',
    weekStart: 1,
    showMeridian: 1,
    autoclose: true,
    isRTL: Metronic.isRTL(),
    format: "yyyy-mm-dd hh:ii",
    pickerPosition: (Metronic.isRTL() ? "bottom-right" : "bottom-left")
});
</script>
{/literal}
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
