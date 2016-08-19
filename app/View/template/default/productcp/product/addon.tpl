{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
<link href="{$smarty.const.URL_CSS}productcp/product/product.min.css" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/icheck/skins/all.css" rel="stylesheet" type="text/css"/>
<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">商品保险 <small>商品名：{$product_name}</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
                    <li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="#">商品</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="/productcp/product/list">商品列表</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/product/addon?id={$product_id}">保险</a></li>
				</ul>
			</div>
			<!--表单-->
			<div class="row">
				<div class="col-md-12">
					<form  method="post" class="form-horizontal form-row-seperated form">
						<input type="hidden" name="prodId" id="prodId" value="{$product_id}" placeholder="商品ID">
						<input type="hidden" value="{$addonids}" id="insIds" placeholder="选择的保险ID">
                    	<div class="portlet">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-edit"></i>内容设置
								</div>
								<div class="pull-right">
									<a href="/productcp/product/list" class="btn default"><i class="fa fa-angle-left"></i> 返回</a>
								</div>
							</div>
						</div>
                        <div class="tab-content">
                        	<div class="margin-bottom-10">
								<a data-toggle="modal" href="#insModal" class="btn yellow"><i class="fa fa-plus"></i> 选择保险 </a>
							</div>
							<div class="row" id="insBox">
								<!--已经添加的保险-->
								{foreach $pruductaddon as $i=>$row}
								<div class="col-sm-12">
									<div class="alert alert-success">
										<span>{$row.title}  ￥{$row.price}/人  {$row.addon_name}</span>
										<a href="javascript:;" style="margin-top:-5px" class="pull-right btn btn-sm red" onclick="ins.del(this,{$row.id})"><i class="fa fa-times"></i> 删除</a>
									</div>
								</div>
								{/foreach}
								<!--已经添加的保险 /-->
							</div>
                        </div>
                        <!--保险选择框-->
						<div class="modal fade bs-modal-lg" id="insModal" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
										<h4 class="modal-title">选择保险</h4>
									</div>
									<div class="modal-body">
										<div class="ins_change_box">
											<!--所有的保险-->
											{foreach $addon as $i=>$row}
											<div class="ins_change_li">
												<label>
													<input type="checkbox"  class="icheck"   value="{$row.id}" data-checkbox="icheckbox_flat-green">
													<div class="ins_change_info">
														<p>{$row.title}<span class="text-danger">￥{$row.price}/人</span></p>
														<p>{$row.addon_name}</p>
													</div>
												</label>
											</div>
											{/foreach}
											<!--所有的保险 /-->
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn green" onclick="ins.add()">确定选择</button>
										<button type="button" class="btn" data-dismiss="modal">关闭</button>
									</div>
								</div>
							</div>
						</div>
                        <!--保险选择框 /-->
                    </form>
				</div>
			</div>
			<!--表单 /-->
		</div>
	</div>
	<!--content-wrapper /-->
	{include file='public/admincp/footer.tpl'}
	
	<script src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/icheck/icheck.min.js"></script>
	<script src="{$smarty.const.URL_JS}productcp/product/productaddon.js"></script>