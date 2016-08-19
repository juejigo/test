{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
<link href="{$smarty.const.URL_CSS}productcp/product/product.min.css" rel="stylesheet" type="text/css"/>
									<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">编辑行程 <small>编辑行程</small></h3>
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
					<li><i class="fa fa-home"></i><a href="#">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="#">商品</a><i class="fa fa-angle-right"></i></li>
   					<li><a href="/productcp/product/list">商品管理</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/product/trip?id={$product_id}">行程</a></li>
				</ul>
			</div>
			<!--表单-->
			<div class="row">
				<div class="col-md-12">
					<form action="/productcp/product/edittrip" class="form-horizontal form-row-seperated" method="post">
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-edit"></i>内容设置
								</div>
								<div class="pull-right">
									<a href="/productcp/product/trip?id={$product_id}" class="btn default"><i class="fa fa-angle-left"></i> 返回</a>
									<button type="reset" class="btn default"><i class="fa fa-refresh"></i> 重置</button>
									<button type="submit" class="btn green"><i class="fa fa-check-circle"></i> 保存</button>
								</div>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="tab-content">
											<div class="form-body">
											<input type="hidden" name="trip_id" value="{$trip_id}">
												<div class="form-group">
			                                    	<label class="col-md-2 control-label">序号：</label>
			                                        <div class="col-md-3">
			                                        	<div class="input-group">
			                                        		<span class="input-group-addon">第</span>
			                                        		<input type="text" class="form-control" id="num" name="sort" value="{$trip.sort}">
			                                        		<span class="input-group-addon">天</span>
			                                        	</div>
			                                            
			                                        </div>
			                                    </div>
												<div class="form-group">
			                                    	<label class="col-md-2 control-label">标题：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control" id="name" name="title"  value="{$trip.title}"  placeholder="行程标题">
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">摘要：</label>
			                                        <div class="col-md-8">
			                                            <textarea class="form-control" id="explain" name="info"  placeholder="摘要" rows="10">{html content=$trip.info}</textarea>
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">详细行程：</label>
			                                        <div class="col-md-8">
			                                            <textarea class="form-control ke" id="detailed" name="content" rows="10">{html content=$trip.content}</textarea>
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label"></label>
			                                        <div class="col-md-8">
			                                        	<div id="tripPickfiles" class="trip_up">&nbsp;</div>
			                                            <div class="form-control-static trip_img_box">
			                                            {foreach $tripimgs as $row}
			                                            {if $row != ""}
			                                           			 <div class="img_l">
			                                           			 <span class="trip_img_del"></span>
			                                           			 <img src="{$row}">
			                                           			 <input type="hidden" name="imgup[]" value="{$row}">
			                                           			 </div>
			                                           			  {/if}
	                                           			 {/foreach}
	                                           			
			                                            </div>
			                                        </div>
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
<script src="{$smarty.const.URL_JS}productcp/product/producttrip.js"></script>