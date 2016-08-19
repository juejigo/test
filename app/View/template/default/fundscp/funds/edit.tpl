{include file='public/admincp/header.tpl'}{include file="public/admincp/siderbar.tpl"}

<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">
	
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<!-- Begin: life time stats -->
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-shopping-cart"></i>用户信息 |  </span>
							</div>
							<div class="actions">
								<a href="#" class="btn default yellow-stripe">
								<i class="fa fa-angle-left"></i>
								<span class="hidden-480">
								Back </span>
								</a>
								<div class="btn-group">
									<a class="btn default yellow-stripe dropdown-toggle" href="#" data-toggle="dropdown">
									<i class="fa fa-cog"></i>
									<span class="hidden-480">
									Tools </span>
									<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-right">
										<li>
											<a href="#">
											Export to Excel </a>
										</li>
										<li>
											<a href="#">
											Export to CSV </a>
										</li>
										<li>
											<a href="#">
											Export to XML </a>
										</li>
										<li class="divider">
										</li>
										<li>
											<a href="#">
											Print Invoice </a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="portlet-body">
							<div class="tabbable">
								<ul class="nav nav-tabs nav-tabs-lg">
									<li class="active">
										<a href="#tab_1" data-toggle="tab">
										详情 </a>
									</li>
									<li>
										<a href="#tab_2" data-toggle="tab">
										  明细
										</a>
									</li>
									
								</ul>
								<div class="tab-content">
								
									<!-- 订单内容 -->
									<div class="tab-pane active" id="tab_1">
									 
										
										<div class="row">
											<div class="col-md-12 col-sm-12">
												<div class="portlet grey-cascade box">
													<div class="portlet-title">
														<div class="caption">
															<i class="fa fa-cogs"></i>用户信息
														</div>
													 
													</div>
													<div class="portlet-body">
														<div class="table-responsive">

															<table class="table table-hover table-bordered table-striped">
															<thead>
															<tr>
																<th>
																	 用户
																</th>
																<th>
																	 姓名
																</th>
																<th>
																	 电话
																</th>
																<th>
																	 银行
																</th>
																<th>
																	 银行卡号
																</th>
																<th>
																	 身份证号码
																</th>	
																
																<th>
																	 身份证正面
																</th>	
																<th>
																	 身份证反面
																</th>																	
																
																<th>
																	 剩余余额
																</th>
																<th>
																	 消费额度
																</th>																
																<th>
																	 日期
																</th>
																 

															</tr>
															</thead>
															<tbody> 
														 	 
															<tr>
															
																<td>
																 
																	{$userList.account}  
																</td>
																<td>
																	{$userList.name}
																</td>
																<td>
																	{$item.mobile}
																</td>
																<td>
																	{$userList.bank}  
																</td>
																
																<td>
																	{$userList.bankcard}  
																</td>																
																
																<td>
																	{$userList.idcard_no}  
																</td>
																
																
																<td>
																	<img src="{$userList.img_1}" width="70" />  
																</td>	
																<td>
																	<img src="{$userList.img_2}" width="70" />   
																</td>																	
																<td>
																	{$userList.balance}  
																</td>	
																<td>
																	{$userList.consumption}  
																</td>																	
																
																<td>
																	{date('Y-m-d H:i:s',$userList.dateline)}  
																</td>	

															</tr>
														 
															</tbody>
															</table>
														 
														 
														 
														 
														</div>
													</div>
												</div>
											</div>
										</div>
										
							  
    <a data-toggle="modal" data-target="#discount_form" href="#" class="btn yellow">
															审核  </a>
													 
				 								
														
														
										
									</div>
									<!-- 订单内容 /-->
									 
									
									 
									
									
									<!-- 发货单 -->
									<div class="tab-pane" id="tab_2">
<div class="row">
											<div class="col-md-12 col-sm-12">
												<div class="portlet grey-cascade box">
													<div class="portlet-title">
														<div class="caption">
															<i class="fa fa-cogs"></i>明细
														</div>
														<div class="actions">
															 
														</div>
													</div>
													<div class="portlet-body">
														<div class="table-responsive">
															<table class="table table-hover table-bordered table-striped">
															<thead>
															<tr>
 
																<th>
																	 订单号
																</th>
																<th>
																	 提现金额
																</th>
																<th>
																	 类型
																</th>
																<th>
																	 备注
																</th>
																<th>
																	审核 状态
																</th>																	
																<th>
																	 提款状态
																</th>																
															</tr>
															</thead>
															<tbody>
															{foreach $info as $item}
															<tr>
															
													 
																<td>
																	{if !empty($item.order_id)}
																	<a target="_blank" href="/ordercp/order/detail?id={$item.order_id}">{$item.order_id}</a>
																	{/if}
																</td>
																<td>
																	
																	{$item.money}
																</td>
																<td>
																	 {$item.desc}
																</td>
																<td>
																	  {$item.detail}
																</td>
																<td>
								                                {if $item.type == 0 && $item.auth == -1}
																	  驳回申请
															 	{else if $item.type == 0 && $item.auth == 1}
								                             		  审核通过
															 	{else if $item.type == 0 && empty($item.auth)}
								                              	  	  未审核
								                                {/if}
																</td>
																
																<td>
								                                {if  empty($item.status)}
																	 未确认
															 	{else if $item.status == 1}
								                             		 已确认
								                                {/if}
																</td>																	
																
															</tr>
															{/foreach}
															</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
                                        									<!-- END EXAMPLE TABLE PORTLET-->								 
			 
								  
									</div>
									<!-- 发货单 /-->
									
							 
							</div>
						</div>
					</div>
					<!-- End: life time stats -->
				</div>
			</div>
			<!-- END PAGE CONTENT-->
			
	</div>
</div>
<!-- END CONTENT -->

{include file='public/admincp/footer.tpl'}
   
<script>
	 $(function(){
		$html='<label class="col-md-3 control-label">提款状态</label>'
		$html +=	   '<div class="col-md-9">'
		$html +=	   '<select class="form-control form-filter" {if $fundslist.status ==1  } selected disabled {/if}  name="status">'
		$html +=       '<option value="">请选择</option>'
		$html +=	   '<option value="0" {if empty($fundslist.status)} selected {/if}>未打款</option>'
		$html +=       '<option value="1"  {if $fundslist.status ==1} selected  {/if}>己打款</option></select>';		 	
	 	 $('#myselect').change(
	 		 function(){ 
	 		 	
	 		 	var $val=$(this).children('option:selected').val();
	 		 	
	 		 	if($val==1){
 
	 		 		  
 				 $('#dis').html($html);  
	 		 	}else{
	 		 		 $('#dis').remove();
	 		 		 
	 		 	}
	 		 	 
	 		 }
	 	)
	 	
	 	{if $fundslist.auth ==1} 
	 	
	 	 $('#dis').html($html);  
	 	 
	 	{/if}
	 	
	 	{if $fundslist.auth =='-1' || $fundslist.status =='1'} 
	 	
	 	 $('#auth').append("<input name=\"auth\" type=\"hidden\"  value=\"{$fundslist.auth}\">");   
	 	 $('#auth').append("<input name=\"status\" type=\"hidden\"  value=\"{$fundslist.status}\">");   
	 	{/if}
	 		 	
	 	 
	 });
</script>

<!-- 设置优惠 -->
<div id="discount_form" class="modal fade">
		<div class="modal-dialog">
				<div class="modal-content">
						<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">提款</h4>
						</div>
						
						<div class="modal-body">
 
						<form class="form-horizontal form-row-seperated" action="/fundscp/funds/check?id={$fundslist.id}" method="post">
								<!-- form-body -->
								<div class="form-body"> 
									 
											<div class="form-group " id='auth'>
											<label class="col-md-3 control-label">审核状态</label>
											<div class="col-md-9">
											 <select id="myselect" class="form-control form-filter" {if $fundslist.auth == 1 || $fundslist.auth == '-1'  } readonly="" {/if}  name="auth">
												<option value="">请选择</option>
												<option value="0" {if empty($fundslist.auth)} selected {/if}>未审核</option>
												<option value="1"  {if $fundslist.auth == 1} selected  {/if}>审核通过</option>
												<option value="-1" {if $fundslist.auth ==='-1'} selected  {/if}>驳回</option>
	 										</select>	
											</div>
										</div> 
										
											<div class="form-group " id="dis">
											
											</div>
										</div> 									
										
										
										<div class="form-group margin-bottom-5 fluid">
											<label class="col-md-3 control-label">备注信息</label>
											<div class="col-md-9">	 
										 <textarea  class="form-control form-filter" name="memo" cols="" rows="">{$fundslist.memo}</textarea>	
											</div>
										</div>
								
								</div>
								<!-- form-body /-->
								
								<!-- form-action -->
								<div class="form-actions fluid">
									<div class="row">
										<div class="col-md-offset-2 col-md-9">
												<input type="hidden" name="id" value="{$params.id}" />
												<button id="discount_submit" class="btn yellow" type="submit">提交</button>
										</div>
									</div>
								</div>
								<!-- form-action /-->
						</form>
						
						</div>
				</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- 设置优惠 /-->
	
	

