 {include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}
  <link href="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen"> 
<script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/jquery-1.8.3.min.js" charset="UTF-8"></script>
 <script type="text/javascript" src="{$smarty.const.URL_MIX}metronic3.6/global/plugins/bootstrap_datetimepicker/js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>

<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">
	
			<h3 class="page-title">提现列表</h3>
			
			<div class="row table-scrollable">
			<form action="/fundscp/funds/list" method="get">
			<table id="datatable_products" class="table table-striped table-bordered table-hover dataTable no-footer" aria-describedby="datatable_products_info" role="grid">
					<thead>
					<tr class="heading" role="row">
							<th width="1%" class="sorting_disabled" rowspan="1" colspan="1">
								<div class="checker"><span><input type="checkbox" class="group-checkable"></span></div>
							</th>
 
							<th width="0%" class="sorting" tabindex="0" aria-controls="datatable_products" rowspan="1" colspan="1">
								用户ID
							</th>
 
							<th width="20%" class="sorting" tabindex="0" aria-controls="datatable_products" rowspan="1" colspan="1">
								 提款金额
							</th>
							<th width="0%" class="sorting" tabindex="0" aria-controls="datatable_products" rowspan="1" colspan="1">
								  提款时间
							</th>
							<th width="0%" class="sorting" tabindex="0" aria-controls="datatable_products" rowspan="1" colspan="1">
								 审核状态
							</th>
							<th width="0%" class="sorting" tabindex="0" aria-controls="datatable_products" rowspan="1" colspan="1">
								提现状态								
							</th>
							</tr>
					<tr class="filter" role="row">
							<td rowspan="1" colspan="1">
							</td>
 
							<td rowspan="1" colspan="1">
								<input type="text" name="member_id" style="width: 150px;" class="form-control form-filter input-sm" value="{if !empty($params.member_id)}{$params.member_id}{/if}">
							</td>
				 
							<td rowspan="1" colspan="1">
								<div class="margin-bottom-5">
									<input type="text" name="price_from"  style="width: 70px;border: 1px solid #e5e5e5;  height: 28px;" value="{if !empty($params.price_from)}{$params.price_from}{/if}">
									
							-	<input type="text" name="price_to"   style="width: 70px;border: 1px solid #e5e5e5;  height: 28px;" value="{if !empty($params.price_to)}{$params.price_to}{/if}">								</div>

							</td>
							<td rowspan="1" colspan="1">
<input size="16" type="text" class="form_datetime input-sm" value="{$params.dateline_from}"  name="dateline_from"  readonly  placeholder="开始时间">                   
<input size="16" type="text"  value="{$params.dateline_to}" name="dateline_to" readonly class="form_datetime input-sm top"  placeholder="结束时间">
  <style>
  .form_datetime{ cursor: not-allowed;   color: #333; ;   width:130px;  border: 1px solid #cccccc;     }
  .top{ margin-top:5px; }
  </style>               
  <script type="text/javascript">
    $(".form_datetime").datetimepicker({ format: 'yyyy-mm-dd hh:ii' });
 
    $(".form_datetime").datetimepicker({ format: 'yyyy-mm-dd hh:ii' }); 
</script></td>
							<td rowspan="1" colspan="1">
								<select class="form-control form-filter input-sm" name="auth">
							<option value="">全部</option> 
							<option  value="1" {if $params.auth==1} selected{/if}>审核通过</option> 
							<option  value="-1" {if $params.auth== '-1'} selected{/if}>被驳回</option> 
								</select>
							</td> 							
 							
							<td rowspan="1" colspan="1">
								<select class="form-control form-filter input-sm" name="status">
							<option value="">全部</option> 
							<option  value="1" {if $params.status==1} selected{/if}>已经打款</option> 
							<option  value="0" {if empty($params.status)} selected{/if}>未打款</option> 
								</select>
							</td>
							
							
							
							<td rowspan="1" colspan="1">
								<div class="margin-bottom-5">
									<button class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> 搜索</button>
								</div>
							</td>
					</tr>
					</thead>
					<tbody>
					</tbody>
			</table>
			</form>
			</div>
			
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box grey-cascade">
						<div class="portlet-title">
							<div class="caption">
								提现列表
							</div>
							<div class="tools">
								<a href="javascript:;" class="collapse">
								</a>
								<a href="#portlet-config" data-toggle="modal" class="config">
								</a>
								<a href="javascript:;" class="reload">
								</a>
								<a href="javascript:;" class="remove">
								</a>
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="row">
									<div class="col-md-6">
										<!--<div class="btn-group">
											<a class="btn green" href="/productcp/product/add?area={$params.area}">
											新增 <i class="fa fa-plus"></i>
											</a>
										</div>-->
									</div>
									<div class="col-md-6">
										<div class="btn-group pull-right">
											<button class="btn dropdown-toggle" data-toggle="dropdown">导出 <i class="fa fa-angle-down"></i>
											</button>
											<ul class="dropdown-menu pull-right">
												<li>
													<a target="_blank" href="/fundscp/funds/exportorder?status=0&auth=1">导出支付EXCEL表</a>
												</li>
								  
											</ul>
										</div>
									</div>
								</div>
							</div>
                            
                <form name="form1" method="post" action="/fundscp/funds/batch">
							<table class="table table-striped table-bordered table-hover" id="sample_1">
							<thead>
							<tr>
								<th class="table-checkbox">
									 
								</th>
								<th>
									 用户ID
								</th>
<!--								<th>
									 订单ID
								</th>-->
								<th>
									 类型
								</th>
								<th>
									 金额
								</th>
								<th>
									 描述
								</th>
								<th>
									 备注
								</th>
                                <th>
									 时间
								</th>
								<th>
									审核状态
								</th>
								<th>
									 打款状态
								</th>							
								<th>
									 
								</th>
							</tr>
							</thead>
							<tbody>
 							{foreach $fundsList as $i => $funds}
 							<tr dataid="{$funds.id}" class="{if $i/2 == 1}odd{/if} gradeX">
								<td>
								
								 <input type="checkbox" name="id[]" {if $funds.auth !=1 } disabled {/if} class="demo" id="ids" value="{$funds.id}" /> 
						 
								 
								</td>
								<td>
										{$funds.member_id}
 
								</td>
<!--								<td>
										{$funds.order_id} 
								</td>-->
								<td> 
								{$funds.desc}
								</td>
								<td>
										{$funds.money}
								</td>
								<td>
									{$funds.detail}
								</td>
                                 <td class="center">
										{$funds.memo}
								</td>
								<td>
										{date('Y-m-d H:i:s',$funds.dateline)}
								</td>
					
								<td class="center"> 
                                {if  $funds.auth == -1}
									  驳回申请
							 	{else if $funds.auth == 1}
                             		  审核通过
							 	{else if empty($funds.auth)}
                              	  	  待处理
                                {/if}
								</td>
								
								<td class="center"> 
                                {if  empty($funds.status)}
									  未打款
							 	{else if $funds.status == 1}
                             		 己打款
							  
                                {/if}
								</td>								
								
 								<td>
					 <a href="/fundscp/funds/edit?id={$funds.id}" class="btn green">查看 <i class="glyphicon glyphicon-edit"></i></a>
									
		 
								</td>
							</tr>
							{/foreach}
							<tr>
                            
                            {if $funds.status !=1 and $funds.status !=1}
                            <td><input type="checkbox" id="all" /></td>
							<td colspan="9">
                              <input  type="submit" value="批量打款"  class="btn yellow">
                            </td>
                            {/if}
                            
							</tr>							
							<tr>
								<td colspan="6">{$pagebar}</td>
							</tr>
							</tbody>
							</table>
  </form>                          
						</div>
					</div>
					<!-- END EXAMPLE TABLE PORTLET-->
				</div>
			</div>
			
	</div>
</div>
<!-- END CONTENT -->

{include file='public/admincp/footer.tpl'}
