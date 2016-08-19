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
																	 等级
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
																	{$item.tel}
																</td>
																<td>
										 	{if empty($userList.group) }
										 	  会员
										 	{else if $userList.group ==1} 
										 	美侠士
										 	{else if $userList.group ==2}
										 	美香主
										 	{else if $userList.group ==3}
										 	美堂主  
										 	{else if $userList.group ==4}  
										 	 美舵主
										    {else if $userList.group ==5} 
										 	 美盟主
										 	 {/if}
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
																	{date('Y-m-d',$userList.dateline)}  
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
																	 状态
																</th>																
															</tr>
															</thead>
															<tbody>
															{foreach $info as $item}
															<tr>
															
													 
																<td>
																	{$item.order_id}
																</td>
																<td>
																	
																	{$item.money}
																</td>
																<td>
																	 {$item.desc}
																</td>
																<td>
																	  {$item.params}
																</td>
																<td>
								                                {if  $item.status == -1}
																	  驳回申请
															 	{else if $item.status == 1}
								                             		  提现成功
															 	{else if empty($item.status)}
								                              	  	  待处理
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
									
									<!-- 订单历史 -->
									<div class="tab-pane" id="tab_3">
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
															<button class="btn dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i>
															</button>
															<ul class="dropdown-menu pull-right">
																<li>
																	<a href="#">
																	Print </a>
																</li>
																<li>
																	<a href="#">
																	Save as PDF </a>
																</li>
																<li>
																	<a href="#">
																	Export to Excel </a>
																</li>
															</ul>
														</div>
													</div>
												</div>
											</div>
											<table class="table table-striped table-bordered table-hover" id="sample_1">
											<thead>
											<tr>
												<th class="table-checkbox">
													<input type="checkbox" class="group-checkable"/>
												</th>
												<th>
													 描述
												</th>
												<th>
													 时间
												</th>
											</tr>
											</thead>
											<tbody>
											{foreach $logs as $log}
											<tr class="{if $i/2 == 1}odd{/if} gradeX">
												<td>
													<input type="checkbox" class="checkboxes" value="1"/>
												</td>
												<td>
													 {$log.params}
												</td>
												<td>
													{date('Y-m-d H:i:s',$log.dateline)}
												</td>
											</tr>
											{/foreach}
											</tbody>
											</table>
									</div>
									<!-- END EXAMPLE TABLE PORTLET-->
									</div>
									<!-- 订单历史 /-->
									
								</div>
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
 


 

<!-- 设置优惠 -->
<div id="discount_form" class="modal fade">
		<div class="modal-dialog">
				<div class="modal-content">
						<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">提款</h4>
						</div>
						
						<div class="modal-body">
 
						<form class="form-horizontal form-row-seperated" action="/fundscp/funds/edit?id={$member.id}" method="post">
								<!-- form-body -->
								<div class="form-body"> 
										<input type="hidden" name="id" value="{$userList.member_id}" />
											<div class="form-group ">
											<label class="col-md-3 control-label">提款状态{$userList.status}</label>
											<div class="col-md-9">
											 <select class="form-control form-filter" {if $userList.status ==1} selected disabled {/if}  name="status">
												<option value="">请选择</option>
												<option value="0" {if empty($member.status)} selected {/if}>待处理</option>
												<option value="1"  {if $member.status ==1} selected  {/if}>成功</option>
												<option value="-1" {if $member.status ==='-1'} selected  {/if}>驳回</option>
	 										</select>	
											</div>
										</div> 
										<div class="form-group margin-bottom-5 fluid">
											<label class="col-md-3 control-label">备注信息</label>
											<div class="col-md-9">	 
										 <textarea  class="form-control form-filter" name="memo" cols="" rows="">{$userList.memo}</textarea>	
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