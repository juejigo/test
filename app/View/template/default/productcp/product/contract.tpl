{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}

<link href="{$smarty.const.URL_CSS}productcp/product/product.min.css" rel="stylesheet" type="text/css"/>
<link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/icheck/skins/all.css" rel="stylesheet" type="text/css"/>
<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">商品合同 <small>商品名：{$contract.product_name}</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
                    <li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="#">商品</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="/productcp/product/list">商品列表</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/product/contract?id={$product_id}">合同</a></li>
				</ul>
			</div>
			<!--表单-->
			<div class="row">
				<div class="col-md-12">
					<form method="post" class="form-horizontal form-row-seperated form">
						<input type="hidden" name="prodId" id="prodId" value="{$product_id}" placeholder="商品ID">
						<input type="hidden" value="{$product.contract_id}" id="conIds" placeholder="选择的合同ID">
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
								<a data-toggle="modal" href="#conModal" class="btn yellow"><i class="fa fa-plus"></i> 选择合同 </a>
							</div>
							<div class="row" id="conBox">
								<!--已经添加的合同-->
								{if $contract.contract_name!=""}
								<div class="col-sm-12">
									<div class="alert alert-success">
										<span>{$contract.contract_name}</span>
										<a href="javascript:;" style="margin-top:-5px" class="pull-right btn btn-sm red" onclick="con.del(this,{$contract.con_id})"><i class="fa fa-times"></i> 删除</a>
									</div>
								</div>
								{/if}
								<!--已经添加的合同 /-->
							</div>
                        </div>
                        <!--合同选择框-->
						<div class="modal fade bs-modal-lg" id="conModal" role="dialog" aria-hidden="true">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
										<h4 class="modal-title">选择合同</h4>
									</div>
									<div class="modal-body">
										<div class="ins_change_box" style="padding: 5px;">
											<!--所有的合同-->
											{foreach $contracts as $i=>$row}
											<div class="ins_change_li">
												<label>
													<input type="radio" class="icheck" name="icheck" value="{$row.id}" data-radio="iradio_flat-green">
													<div class="ins_change_info">
														<p>{$row.contract_name}</p>
													</div>
												</label>
											</div>
											{/foreach}
											<!--所有的合同 /-->
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn green" onclick="con.add()">确定选择</button>
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

	