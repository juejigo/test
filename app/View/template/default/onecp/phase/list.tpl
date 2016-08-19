 {include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
<link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen"> 
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/jquery-1.8.3.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">期数列表 <small>查看和管理一元夺宝期数信息</small></h3>
			<!--路径导航-->
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li><i class="fa fa-home"></i><a href="/admincp">首页</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/onecp/phase/list">一元夺宝</a><i class="fa fa-angle-right"></i></li>
					<li><a href="/onecp/phase/list">期数列表</a></li>
				</ul>
			</div>
			<!--搜索栏-->
			<form action="" id="search" name="search" method="get">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr role="row">
								<th class="sorting_asc" width="10%">ID</th>
								<th class="sorting_asc" width="20%">商品名</th>
								<th class="sorting_asc" width="10%">期数</th>
								<th class="sorting_asc" width="20%">日期选择</th>
								<th class="sorting_asc" width="10%">状态</th>
								<th class="sorting_asc">操作</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<input type="search" class="form-control" placeholder="期数ID" name="id" aria-controls="sample_1" value="{$params.id}">
								</td>
								<td>
									<input type="search" class="form-control" placeholder="商品名" name="product_name" aria-controls="sample_1" value="{$params.product_name}">
								</td>
								<td>
									<input type="search" class="form-control" placeholder="期数" name="no" aria-controls="sample_1" value="{$params.no}">
								</td>
								<td>
									<div data-date-format="yyyy-mm-dd" class="input-group date date-picker margin-bottom-5">
										<input type="text" placeholder="开始时间" name="dateline_from" readonly="" class="form-control form-filter input-sm" value="{$params.dateline_from}">
										<span class="input-group-btn">
										<button type="button" class="btn btn-sm default"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
									<div data-date-format="yyyy-mm-dd" class="input-group date date-picker">
										<input type="text" placeholder="截至时间" name="dateline_to" readonly="" class="form-control form-filter input-sm" value="{$params.dateline_to}">
										<span class="input-group-btn">
										<button type="button" class="btn btn-sm default"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</td>
								<td>
									<select class="form-control" name="status">
										<option value="">全部</option>
										<option value="0"{if $params.status === '0'}selected="selected"{/if}>待上架</option>
										<option value="1"{if $params.status == 1}selected="selected"{/if}>进行中</option>
										<option value="2"{if $params.status == 2}selected="selected"{/if}>待揭晓</option>
										<option value="3"{if $params.status == 3}selected="selected"{/if}>已揭晓</option>
										<option value="4"{if $params.status == 4}selected="selected"{/if}>已到期</option>
									</select>
								</td>
								<td>
									<div class="margin-bottom-5">
										<input type="hidden" name="area" value="0">
										<button class="btn btn-sm yellow filter-submit margin-bottom" type="submit"><i class="fa fa-search"></i> 搜索</button>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</form>
			<!--搜索栏 /-->

			<!--表格列表-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box grey-cascade">
						<!--表格标题-->
						<div class="portlet-title">
							<div class="caption">期数列表   {$count}</div>
						</div>
						<div class="portlet-body">
							<!--工具-->
							<div class="table-toolbar">
								<div class="row">
									<div class="col-md-6">
										<a class="btn green" href="/onecp/phase/add">新增 <i class="fa fa-plus"></i></a>
										<a class="checkDel btn red" href="javascript:;">删除勾选项</a>
									</div>
								</div>
							</div>
							<!--表格-->
							<div class="dataTables_wrapper no-footer" id="sample_1_wrapper">
								<div class="table-scrollable">
									<table class="table table-striped table-bordered table-hover dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
										<thead>
											<tr role="row">
												<th width="80"><input type="checkbox" class="group-checkable" id="allCheck">ID</th>
												<th width="300">商品名</th>
												<th width="80">期数</th>
												<th width="100">图片</th>
												<th width="100">价格</th>
												<th width="170">开始时间/结束时间</th>
												<th width="100">状态</th>
												<th width="350">操作</th>
											</tr>
										</thead>
										<tbody>
										{foreach $phaseList as $phase}
											<tr data-id="{$phase.id}" class="gradeX odd" role="row"> 
												<td><input type="checkbox" class="group-checkable checkbox">{$phase.id}</td>
												<td>{$phase.product_name}</td>
												<td>{$phase.no}</td>
												<td><img alt="" src="{$phase.image}" width="60" height="60"></td>
												<td>{$phase.product_price}</td>
												<td>{if $phase.start_time == 0}自动上架{else}{date("Y-m-d H:i:s",$phase.start_time)}{/if} 至  {if $phase.end_time == 0}无限期{else}{date("Y-m-d H:i:s",$phase.end_time)}{/if}</td>
												<td>
												{if $phase.status == 0}<span class="label label-warning">待上架</span>
												{else if $phase.status == 1}<span class="label label-danger">进行中</span>
												{else if $phase.status == 2}<span class="label label-info">待揭晓</span>
												{else if $phase.status == 3}<span class="label label-success">已揭晓</span>
												{else if $phase.status == 4}<span class="label label-default">已到期</span>
												{/if}
												</td>
												<td>
													<a href="/onecp/phase/edit?id={$phase.id}" class="btn green">编辑 <i class="glyphicon glyphicon-edit"></i></a>
													<a href="/onecp/phase/detail?id={$phase.id}" class="btn btn yellow">详情 <i class="fa fa-search"></i></a>		
													<a href="/onecp/order/list?phase_id={$phase.id}" class="btn btn blue">记录 <i class="fa fa-search"></i></a>
													<a href="javascript:;" class="delete btn red">删除 <i class="glyphicon glyphicon-remove"></i></a></td>
											</tr>
										{/foreach}											
											<tr>
												<td colspan="10">{$pagebar}</td>
											</tr>
										</tbody>							
									</table>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
			<!--表格结束 /-->
		</div>
	</div>
	<!--content-wrapper /-->
</div>
<!-- container /-->
{include file='public/admincp/footer.tpl'}
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
</body>
<!-- END BODY -->
</html>
