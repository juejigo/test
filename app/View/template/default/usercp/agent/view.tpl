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
								<i class="fa fa-shopping-cart"></i>代理商信息 |  </span>
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
											<a href="/usercp/agent/exportorder?id={$id}" target="_blank">
											Export to Excel </a>
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
										会员信息 </a>
									</li>
									<li>
										<a href="#tab_2" data-toggle="tab">
										  会员消费
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
															<i class="fa fa-cogs"></i>会员信息 （共{$total}人）
														</div>
													 
													</div>
													<div class="portlet-body">
														<div class="table-responsive">

															<table class="table table-hover table-bordered table-striped">
															<thead>
															<tr>
																<th>
																	 ID
																</th>
																<th>
																	 用户
																</th>
																<th>
																	 消费金额

																</th>																
																<th>
																	 当前状态
																</th>
																<th>
																	 注册时间
																</th>
																 

															</tr>
															</thead>
															<tbody> 
														 	  
																
														 {foreach $arr as $key => $auth}
														 <tr>
															 	<td>
																	{$auth.id}  
																</td> 
															 	<td>
																	{$auth.account}  
																</td>
															 	<td>
																	{$auth.consumption}  
																</td>	
																
															 	<td>
																   {if $auth.status==1}
																	      正常
																    {else}
																	    冻结  
																 
																	{/if} 
																</td>																
																
															 	<td>
																 
																	 {date('Y-m-d H:i:s',$auth.register_time)}
																</td>																	
															</tr>	
														{/foreach}
																

															<tr>
																<td colspan="5">{$pagebar}</td>
															</tr>
														 
															</tbody>
															</table>
														 
														 
														 
														 
														</div>
													</div>
												</div>
											</div>
										</div>
					  
									</div>
									<!-- 订单内容 /-->
									 
									
									 
									
									
									<!-- 发货单 -->
									<div class="tab-pane" id="tab_2">
<div class="row">
											<div class="col-md-12 col-sm-12">
												<div class="portlet grey-cascade box">
													<div class="portlet-title">
														<div class="caption">
															<i class="fa fa-cogs"></i>会员消费明细  (共{count($orderarr)}人)
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
																	 ID
																</th>
																<th>
																	 电话
																</th>
																<th>
																	 消费时间
																</th>																
																<th>
																	 消费金额
																</th>
 
																 

															</tr>
															</thead>
															<tbody> 
														 	  
																
														 {foreach $orderarr as $key => $auth}
														 <tr>
															 	<td>
																	{$auth.id}  
																</td> 
															 	<td>
																	{$auth.mobile}  
																</td>
															 	<td>
																 
																	 {date('Y-m-d H:i:s',$auth.pay_time)}
																</td>	
																
															 	<td>
																	{$auth.pay_amount}  
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
 

