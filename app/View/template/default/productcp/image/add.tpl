{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
<link href="{$smarty.const.URL_CSS}productcp/product/product.min.css" rel="stylesheet" type="text/css"/>



	<!-- 左侧列表 /-->
	<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">商品主图 <small>商品名：{$defaultimg.product_name}</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
                    <li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="#">商品</a><i class="fa fa-angle-right"></i></li>
                    <li><a href="/productcp/product/list">商品管理</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/productcp/image/add?id={$defaultimg.id}">主图</a></li>
				</ul>
			</div>
			<!--表单-->
			<div class="row">
				<div class="col-md-12">
					<form method="post" class="form-horizontal form-row-seperated form" action="../a.php">
						<input type="hidden" name="prodId" id="prodId" value="{$product_id}" placeholder="商品ID">
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
                        	<!--图片信息form-->
							<div id="handleContainer" class="margin-bottom-10">
								<a id="handlePickfiles" href="javascript:;" class="btn yellow"><i class="fa fa-plus"></i> 选择图片 </a>
								<a id="handleUploadfiles" href="javascript:;" class="btn green"><i class="fa fa-share"></i> 上传图片 </a>
							</div>
							<div class="row">
								<div id="handleFilelist" class="col-md-6 col-sm-12"></div>
							</div>
							<!--图片容器-->
							<div class="row">
								<ul class="col-sm-12" id="handleImgBox">
								{foreach $images as $i=>$data}
									<li class="handle_img" data-imgid="{$data.id}">
										<img src="{$data.image}" draggable="false">
										<div class="handle_img_fun">
										{if $data['image'] == $defaultimg.image}
										<button type="button" class="btn btn-default btn-xs handleDefault btn-success"> 默认图片 </button>
										{else}
										<button type="button" class="btn btn-default btn-xs handleDefault"> 设为默认图片 </button>
										{/if}					
									<button type="button" class="btn btn-default btn-xs handleDelImg"><i class="fa fa-trash-o"></i> 删除 </button>
										</div>
									</li>
									{/foreach}
								</ul>
							</div>
							<!--图片容器 /-->
							<div class="panel panel-success">
								<div class="panel-heading">
									<h3 class="panel-title">温馨提示</h3>
								</div>
								<div class="panel-body">
									<ul>
										<li>上传的图片不能超过4M。</li>
										<li>只允许上传(jpg,jpng,gif,png)格式的图片。</li>
									</ul>
								</div>
							</div>
                            <!--图片信息form /-->
                        </div>
                    </form>
				</div>
			</div>
			<!--表单 /-->
		</div>
	</div>

<!-- container /-->
{include file='public/admincp/footer.tpl'}
<script src="{$smarty.const.URL_JS}productcp/product/productphoto.js"></script>