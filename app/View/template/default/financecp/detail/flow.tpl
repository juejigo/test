 {include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
	<!--content-wrapper-->
	<div class="page-content-wrapper">
		<div class="page-content" style="min-height:812px">
			<!--标题栏-->
			<h3 class="page-title">明细 <small>流水账列表</small></h3>

			<!--搜索栏-->
			<form action="" method="get">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr role="row">
								<th class="sorting_asc" width="30%">交易流水编号</th>
								<th class="sorting_asc" width="20%">项目名称</th>
								<th class="sorting_asc" width="20%">具体金额</th>
								<th class="sorting_asc" width="20%">日期选择</th>
								<th class="sorting_asc" width="10%">操作</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<input type="search" class="form-control" placeholder="交易流水号" name="out_id" aria-controls="sample_1">
								</td>
								<td>
									<input type="search" class="form-control" placeholder="项目" name="item_name" aria-controls="sample_1">
								</td>
								<td>
									<input type="search" class="form-control" placeholder="金额" name="pay_amount" aria-controls="sample_1">
								</td>
								<td>
									<div data-date-format="yyyy-mm-dd" class="input-group date date-picker margin-bottom-5">
										<input type="text" placeholder="开始时间" name="dateline_from" readonly="" class="form-control form-filter input-sm" value="">
										<span class="input-group-btn">
										<button type="button" class="btn btn-sm default"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
									<div data-date-format="yyyy-mm-dd" class="input-group date date-picker">
										<input type="text" placeholder="截至时间" name="dateline_to" readonly="" class="form-control form-filter input-sm" value="">
										<span class="input-group-btn">
										<button type="button" class="btn btn-sm default"><i class="fa fa-calendar"></i></button>
										</span>
									</div>
								</td>
								<td>
									<div class="margin-bottom-5">
										<!-- <input type="hidden" name="area" value="0"> -->
										<button class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> 搜索</button>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</form>
			<!--表格列表-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box grey-cascade">
						<!--表格标题-->
						<div class="portlet-title">
							<div class="caption">提现列表{$count}</div>
						</div>
						<div class="portlet-body">
							<!--工具-->
							<div class="table-toolbar">
								<div class="row">
									<div class="col-md-12">
										<div class="pull-right">
											<div class="btn-group">
												<button type="button" class="btn default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">支付宝 <i class="fa fa-angle-down"></i></button>
												<ul class="dropdown-menu pull-right">
													<li><a href="">支付宝</a></li>
													<li><a href="">微信</a></li>
												</ul>
											</div>
											<div class="btn-group">
												<button type="button" class="btn default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">导出 <i class="fa fa-angle-down"></i></button>
												<ul class="dropdown-menu pull-right">
													<li><a target="_blank" href="/fundscp/funds/exportorder?status=0&amp;auth=1">导出支付EXCEL表</a></li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!--表格-->
							<div class="dataTables_wrapper no-footer" id="sample_1_wrapper">
								<div class="table-scrollable">
									<table class="table table-striped table-bordered table-hover dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
										<thead>
											<tr role="row">
												<th class="sorting_asc">交易流水编号</th>
												<th class="sorting_asc">日期</th>
												<th class="sorting_asc">项目</th>
												<th class="sorting_asc">去向</th>
												<th class="sorting_asc">金额</th>
											</tr>
										</thead>
										<tbody>
											 {foreach $outidList as $i => $outid}
											<tr dataid="{$outid.out_id}" class="{if $i/2 == 1}odd{/if} gradeX">
												<td>{$outid.out_id}</td>
												<td>
													{if !empty($outid.dateline)}{date('Y-m-d',$outid.dateline)}{/if}
												</td>
												<td>{$outid.item_name}</td>
												<td>
													{if ($outid.status==3 or $outid.status==1 or $outid.status==2 or $outid.status==20)}<span>收入</span>{/if}
													{if ($outid.status==13)}<span>退款</span>{/if}
													{if ($outid.status==0)}<span>待付款</span>{/if}
                                                    {if ($outid.status==10 or $outid.status==11 or $outid.status==12)}<span>待退款</span>{/if}
												</td>
												<td>{$outid.pay_amount}</td>
											</tr>
											{/foreach} 
											<tr>
								                 <td colspan="6">{$pagebar}</td>
							                </tr>
										</tbody>
									</table>
								</div>
							</div>
							<!--分页-->
							 
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
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="page-footer-inner">
		 2014 &copy; Metronic by keenthemes.
	</div>
	<div class="scroll-to-top">
		<i class="icon-arrow-up"></i>
	</div>
</div>
<!-- END FOOTER -->

<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
{include file='public/admincp/footer.tpl'}
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->

<!-- END PAGE LEVEL SCRIPTS -->
{literal}
<script>
$(function() {    
    Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
	//添加日期选择功能 
    $(".date-picker").datepicker({ 
		rtl: Metronic.isRTL(),
        autoclose: true,
    });
});
</script>
{/literal}
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
