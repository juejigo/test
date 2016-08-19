     {include file='public/fr/headeruser.tpl'}
  <!--中间内容-->

  <div class="main">
      <form id="orderFill" class="pt45" action="/order/order/ajax?op=create">
        <input type="hidden" name="items[0][id]" value="{$params.items.0.id}" placeholder="产品ID">
        <input type="hidden" name="items[0][is_adult]" value="{$params.items.0.is_adult}" placeholder="成人">
        <input type="hidden" name="items[0][num]" value="{$params.items.0.num}" placeholder="数量">
        <input type="hidden" name="items[1][id]" value="{$params.items.1.id}" placeholder="产品ID">
        <input type="hidden" name="items[1][is_adult]" value="{$params.items.1.is_adult}" placeholder="小孩">
        <input type="hidden" name="items[1][num]" value="{$params.items.1.num}" placeholder="数量">
        <input type="hidden" id="totalPeople" value="{$product.num}">
        <input type="hidden" id="totalAmount" value="{$pay_amount}">
        <!--左侧-->
        <div class="order_left">
          <!--订单信息-->
          <div class="order_info">
            <h1>{$product.product_name}</h1>
            <div class="order_form">
              <div class="row clearfix">
                <div class="l">出发城市：</div>
                <div class="r">{$product.region_name}</div>
              </div>
              <div class="row clearfix">
                <div class="l">出发日期：</div>
                <div class="r">{date("Y-m-d",$product.travel_date)}</div>
              </div>
              <div class="row clearfix">
                <div class="l">出行人数：</div>
                <div class="r">{$product.adult_num} {$product.child_num}<div class="ycolor th"><i class="prompt"></i>标准<div class="tip">
                <span class="tip_j"></span>{$product.information}</div></div></div>

              </div>
              <div class="row clearfix">
                <div class="l">备注：</div>
                <div class="r"><textarea name="remarks" rows="8" cols="40"></textarea></div>
              </div>
            </div>
            <div class="order_more">
              <h3>出行人群限制</h3>
            {html content=$product.travel_restrictions}
              <div class="agree" id="travel"><i class="check"></i>我确认所有旅客满足出行条件</div>
            </div>
          </div>
          <!--订单信息 /-->
          <!--联系人信息-->
          <div class="order_contact_row">
            <h2>联系人信息</h2>
            <div class="order_contact">
              <div class="text_group">
                  <label class="label w200">姓名：</label>
                  <div class="input">
                    <input type="text" name="buyer_name" id="realname" placeholder="姓名" class="w380">
                  </div>
              </div>
              <div class="text_group">
                  <label class="label w200">手机：</label>
                  <div class="input">
                    <input type="text" name="mobile" id="phone" placeholder="手机号码" class="w380">
                  </div>
              </div>
              <div class="text_group">
                  <label class="label w200">邮箱：</label>
                  <div class="input">
                    <input type="text" name="email" id="email" placeholder="邮箱" class="w380">
                  </div>
              </div>
            </div>
          </div>
          <!--联系人信息 /-->
          <!--出行人信息-->
          <div class="order_contact_row" id="travelPeople">
            <h2>出行人信息</h2>
            <div class="order_contact">
              <div class="add_tourist"><i class="add"></i>新增出行人</div>
              <div class="order_prompt">
             {html content=$product.travel_restrictions}
              </div>
              <div class="order_person_list">
                <ul>
                {foreach $tourist_list as $row}
                  <li data-id="{$row.id}"><i class="check"></i>{$row.tourist_name}</li>
                  {/foreach}
                </ul>
              </div>
              <div class="order_person_table">
                  <div class="title">
                    <span class="fl">姓名</span>
                    <span class="fl">证件类型</span>
                    <span class="fl">证件号码</span>
                    <span class="fl">手机号码</span>
                  </div>
                  <div class="list">
                    <ul>

                    </ul>
                  </div>
              </div>
            </div>
          </div>
          <!--出行人信息 /-->
          <!--保险信息-->
          {if addon != ""}
          <div class="order_contact_row">
            <h2>保险信息</h2>
            <div class="order_contact">
              <div class="order_insurance_table">
                  <div class="title">
                    <span class="fl xz">险种</span>
                    <span class="fl name">名称</span>
                    <span class="fl price">单价</span>
                    <span class="fl op">选择</span>
                  </div>
                  <div class="list" id="insuranceList">
                    <ul>
                    {foreach $addon  as $i=>$row}
                        <li data-id="{$row.insurance_id}" data-money="{$row.price}" data-title="{$row.title}" class="row {if $i >0}none{/if}">
                            <span class="fl xz">{$row.type}</span>
                            <span class="fl name">{$row.title}</span>
                            <span class="fl price ycolor">￥{$row.price}/人</span>
                            <span class="fl op"><i class="check"></i></span>
                        </li>
                        <li class="detail">
							{$row.info}
                        </li>
                        {/foreach}
                    </ul>
                  </div>
                  <div class="operate"><a href="javascript:;">展开</a></div>
              </div>
            </div>
          </div>
          {/if}
          <!--保险信息 /-->
          <!--补差价说明-->
          <div class="order_contact_row">
            <h2>补差价说明</h2>
            <div class="order_contact">
              <div class="order_prompt">
              {html content=$product.information}
              </div>
            </div>
          </div>
          <!--补差价说明 /-->
          <!--预订须知-->
          <div class="order_contact_row">
            <h2>预订须知</h2>
            <div class="order_contact">
                <div class="notice_details">
                {html content=$product.cost_need}
                </div>
            </div>
          </div>
          <!--预订须知 /-->
          <div class="order_next">
            <div class="agree" id="read"><i class="check"></i>我已经阅读并接受<a href="" target="_blank">预订须知、合同、保险等条款</a></div>
            <div class="order_amount"><label>订单金额：</label><span>￥{$pay_amount}</span></div>
            <button type="submit" class="btn btn_ycolor btn_raidus place_order">我已阅读预订须知，提交订单</button>
          </div>
        </div>
        <!--左侧 /-->
        <!--右侧-->
        <div class="order_right">
          <div id="right">
            <h2>结算信息</h2>
            <dl>
              <dt>出游团费</dt>
              {if $product.child_num != ""}
              <dd><span class="number">{$product.child_num}</span> <span class="price">￥{$product.child_price}</span><span class="amount">￥{$product.child_total}</span></dd>
              {/if}
                 {if $product.adult_num != ""}
              <dd><span class="number">{$product.adult_num}</span><span class="price">￥{$product.adult_price}</span><span class="amount">￥{$product.adult_total}</span></dd>
              {/if}
              <div id="insurance">

              </div>
            </dl>
            <div class="order_right_bottom">
              <label>订单金额：</label><span class="amount">￥{$pay_amount}</span>
              <p><button type="submit" class="btn btn_ycolor btn_raidus btn_block place_order" style="width:180px;">提交订单</button></p>
            </div>
          </div>
        </div>
        <!--右侧 /-->
      </form>
      <!--增加出行人-->
      <div class="popup">
        <div class="popup_title">新增出行人<i class="popup_close"></i></div>
        <div class="popup_body">
          <form action="/order/order/ajax?op=addtourist" id="addTourist">
            <div class="text_group">
                <label class="label w200">姓名：</label>
                <div class="input">
                  <input type="text" name="tourist_name" id="tourist_name" placeholder="输入姓名" class="w380">
                  <p class="error" for="code"></p>
                </div>
            </div>
            <div class="text_group">
                <label class="label w200">性别：</label>
                <div class="input">
                  <select class="" name="sex">
                    <option value="1">男</option>
                    <option value="0">女</option>
                  </select>
                </div>
            </div>
            <div class="text_group">
                <label class="label w200">手机号：</label>
                <div class="input">
                  <input type="text" name="mobile" id="mobile" placeholder="输入手机号" class="w380">
                  <p class="error" for="code"></p>
                </div>
            </div>
            <div class="text_group">
                <label class="label w200">证件：</label>
                <div class="input">
                  <select class="" name="cert_type" id="cert_type">
                    <option value="1">身份证</option>
                    <option value="2">护照</option>
                  </select>
                </div>
            </div>
            <div class="text_group">
                <label class="label w200">证件号：</label>
                <div class="input">
                  <input type="text" name="cert_num" id="cert_num" placeholder="输入证件号" class="w380">
                  <p class="error" for="code"></p>
                </div>
            </div>
            <div class="text_group">
                <label class="label w200">有效期：</label>
                <div class="input">
                  <input type="text" name="cert_deadline" id="cert_deadline" placeholder="输入有效期" class="w380">
                  <p class="error" for="code"></p>
                  <i class="csrq"></i>
                </div>
            </div>
            <div class="text_group">
                <label class="label w200"></label>
                <div class="input_nob w200">
                    <button type="submit" class="btn btn_ycolor btn_raidus">确认添加</button>
                    <button type="reset" class="btn btn_raidus">重置</button>
                    <p class="error" for="all"></p>
                </div>
            </div>
          </form>
        </div>
      </div>
      <div class="mask"></div>
      <!--增加出行人-->
  </div>
  <!--中间内容 /-->
    {include file='public/fr/footer.tpl'}

