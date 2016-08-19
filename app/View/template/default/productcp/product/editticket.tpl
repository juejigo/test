<div class="form-body">
												<input type="hidden" name="flyId" id="flyId" value="{$ticket.id}">
												<div class="form-group">
			                                    	<label class="col-md-2 control-label">往返选择：</label>
			                                        <div class="col-md-8">
			                                            <select class="form-control type">
															<option value="">请选择</option>
															<option value="0" {if $ticket.type == 0}selected="selected"{/if}>去程</option>
															<option value="1" {if $ticket.type == 1}selected="selected"{/if}>返程</option>
														</select>
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
													<label class="col-md-2 control-label">产品日期：</label>
													<div class="col-md-8">
														<select class="form-control prod_time">
															<option value="">请选择</option>
															{foreach $travel_date as $row}
															<option value="{$row.id}"  {if $ticket.product_id == $row.id} selected="selected" {/if}> {date("Y-m-d",$row.travel_date)}</option>
															{/foreach}
														</select>
														<span class="help-block text-primary">选择产品规格对应日期，不能重复选择。</span>
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-2 control-label">时间：</label>
													<div class="col-md-8">
														<input type="text" class="form-control reservationtime" placeholder="选择时间" value="{date('Y-m-d H:i:s',$ticket.go_time)}至{date('Y-m-d H:i:s',$ticket.return_time)}">
														<input type="hidden" class="start_time"  name="go_time" value="{date('Y-m-d H:i:s',$ticket.go_time)}" >
														<input type="hidden" class="end_time" name=""   value="{date('Y-m-d H:i:s',$ticket.return_time)}">														
													</div>
												</div>
												<div class="form-group">
			                                    	<label class="col-md-2 control-label">出发地：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control input-inline go_address" placeholder="格式：省份-城市"  name="go_area" value="{$ticket.go_area}">
			                                            <input type="text" class="form-control input-inline go_airport" placeholder="机场名" name="go_airport"  value="{$ticket.go_airport}">
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">目的地：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control input-inline back_address" placeholder="格式：国家-城市" name="return_area" value="{$ticket.return_area}" >
			                                            <input type="text" class="form-control input-inline back_airport" placeholder="机场名" name="return_airport" value="{$ticket.return_airport}">
			                                        </div>
			                                    </div>
												<div class="form-group">
			                                    	<label class="col-md-2 control-label">航空公司：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control company" placeholder="航空公司" name="company"  value="{$ticket.company}">
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">航班：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control flight" placeholder="航班" name="flight" value="{$ticket.flight}">
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">舱位：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control seat" placeholder="舱位" name="berths" value="{$ticket.berths}" >
			                                        </div>
			                                    </div>
			                                    <div class="form-group">
			                                    	<label class="col-md-2 control-label">价格：</label>
			                                        <div class="col-md-8">
			                                            <input type="text" class="form-control price" placeholder="价格" name="price" value="{$ticket.price}">
			                                        </div>
			                                    </div>
											</div>