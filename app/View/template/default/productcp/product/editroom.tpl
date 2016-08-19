{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
<link href="{$smarty.const.URL_CSS}productcp/product/product.min.css" rel="stylesheet" type="text/css"/>
									<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">编辑房间 <small>编辑房间</small></h3>
				<!-- 错误提醒 -->
				{if $error->hasError()}
				{foreach $error->getAll() as $e}
				<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
						<strong>错误！</strong> {array_shift($e)}
				</div>
				{/foreach}
				{/if}
				<!-- 错误提醒 /-->
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/product/list">商品</a><i class="fa fa-angle-right"></i></li>
   					<li><a href="/productcp/product/list">商品管理</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/product/editroom?id={$params.id}">修改房间</a></li>
				</ul>
			</div>
			<!--表单-->
			<div class="row">
				<div class="col-md-12">
					<form action="/productcp/product/editroom?id={$params.id}" class="form-horizontal form-row-seperated" method="post">
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-edit"></i>内容设置
								</div>
								<div class="pull-right">
									<a href="Javascript:history.go(-1);" class="btn default"><i class="fa fa-angle-left"></i> 返回</a>
									<button type="reset" class="btn default"><i class="fa fa-refresh"></i> 重置</button>
									<button type="submit" class="btn green"><i class="fa fa-check-circle"></i> 保存</button>
								</div>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="tab-content">
								<div class="form-body">
									<div class="form-group">
                                    	<label class="col-md-2 control-label">房间名：</label>
                                        <div class="col-md-8">
                                        	<input type="hidden" name="id" value="{$params.id}">
                                            <input type="text" class="form-control" id="addon_name" name="addon_name" value="{$room.addon_name}">
                                        </div>
                                    </div>
                                    <div class="form-group">
										<label class="col-md-2 control-label">图片：</label>
										<div class="col-md-8">
												<input data-toggle="modal" data-target="#image_uploader" type="text" class="form-control" name="image" id = "image" value="{$room.image}">
										</div>
									</div>
                                    <div class="form-group">
                                    	<label class="col-md-2 control-label">可住人数：</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="num" name="num" value="{$room.extra.num}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    	<label class="col-md-2 control-label">面积：</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="area" name="area" value="{$room.extra.area}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    	<label class="col-md-2 control-label">价格：</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="price" name="price" value="{$room.price}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    	<label class="col-md-2 control-label">楼层：</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="floor" name="floor" value="{$room.extra.floor}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    	<label class="col-md-2 control-label">库存：</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="stock" name="stock" value="{$room.extra.stock}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    	<label class="col-md-2 control-label">设施：</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control ke" id="facilities" name="facilities" rows="10" >{$room.extra.facilities}</textarea>
                                        </div>
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
		{include file='public/admincp/footer.tpl'}
<script src="{$smarty.const.URL_JS}lib/kindeditor/kindeditor-all-min.js"></script>
<script src="{$smarty.const.URL_JS}lib/kindeditor/lang/zh_CN.js"></script>
<script src="{$smarty.const.URL_JS}productcp/product/room.js"></script>