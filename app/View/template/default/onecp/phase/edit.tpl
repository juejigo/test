 {include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
 <link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen"> 
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/jquery-1.8.3.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
	<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">编辑期数 <small>编辑期数</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/onecp/phase/list">一元夺宝</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/onecp/phase/list">期数列表</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/onecp/phase/edit?id={$params.id}">编辑期数</a></li>
				</ul>
			</div>
			<!--表单-->
			<div class="row">
				<div class="col-md-12">
					<form action="/onecp/phase/edit?id={$params.id}" class="form-horizontal form-row-seperated" method="post">
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-edit"></i>填写信息
								</div>
								<div class="pull-right">
									<a href="/onecp/phase/list" class="btn default"><i class="fa fa-angle-left"></i> 返回</a>
									<button type="reset" class="btn default"><i class="fa fa-refresh"></i> 重置</button>
									<button type="submit" class="btn green"><i class="fa fa-check-circle"></i> 保存</button>
								</div>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="tab-content">
								<div class="form-body">
                  					<div class="form-group">
										<label class="control-label col-md-2">商品名称 </label>
										<div class="col-md-6"><input type="text" class="form-control" name="product_name" placeholder="请输入商品名称" value="{$phase.product_name}"></div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">商品图片 </label>
										<div class="col-md-6"><input data-toggle="modal" data-target="#image_uploader" name="image" class="form-control" placeholder="点击添加图片" value="{$phase.image}"></div>
									</div>
                 				 	<div class="form-group">
										<label class="control-label col-md-2">商品价格 </label>
										<div class="col-md-6"><input type="text" class="form-control" name="product_price" placeholder="请输入对应商品价格" value="{$phase.product_price}"></div>
									</div>
                  					<div class="form-group">
										<label class="control-label col-md-2">夺宝价 </label>
										<div class="col-md-6"><input type="text" class="form-control" name="price" placeholder="请输入夺宝价" value="{$phase.price}"></div>
									</div>
                  					<div class="form-group">
										<label class="control-label col-md-2">限购 </label>
										<div class="col-md-6"><input type="text" class="form-control" name="limit_num" placeholder="请输入限购次数" value="{$phase.limit_num}"></div>
									</div>
                  					<div class="form-group">
										<label class="control-label col-md-2">开始时间 </label>
										<div class="col-md-6"><input type="text" class="form-control form_datetime" name="start_time" placeholder="开始时间" value="{date("Y-m-d H:i:s",$phase.start_time)}"></div>
									</div>
                  					<div class="form-group">
										<label class="control-label col-md-2">结束时间 </label>
										<div class="col-md-6"><input type="text" class="form-control form_datetime" name="end_time" placeholder="结束时间" value="{date("Y-m-d H:i:s",$phase.end_time)}"></div>
									</div>
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
<!-- container /-->
{include file='public/admincp/footer.tpl'}
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
</body>
<!-- END BODY -->
</html>
