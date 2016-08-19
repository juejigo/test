<?php

class Pay_WxwebController extends Core2_Controller_Action_Fr
{
	/**
	 *  初始化
	 */
	protected $rid; //记录id
	protected $record_status = 1;//0关闭 1开启
	protected $callback_data;//回调数据
	protected $callback_xml;//回调数据xml

	public function init()
	{
		parent::init();
		$this->models['order'] = new Model_Order();
		$this->models['one_order'] = new Model_OneOrder();
		$this->models['product'] = new Model_Product();
		$this->models['member'] = new Model_Member();
		$this->models['funds'] = new Model_Funds();
		$this->models['order_log'] = new Model_OrderLog();
		include "lib/api/wxwebpay/lib/WxPay.Api.php";
		include "lib/api/wxwebpay/unit/WxPay.JsApiPay.php";
		
		//存储微信的回调
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $result = new WxPayResults();
		$data = $this->callback_data = $result->FromXml($xml);
		$notify_status = WxPayResults::Init2($xml);
		//dump($notify_status);exit;
		if($notify_status == FALSE){
			$error_msg = '签名错误';
		}else{
			$error_msg = '签名成功';
		}
		if($this->record_status){
		  $this->models['record'] = new Model_PayNotify();//回调信息记录
		  $select = $this->_db->select()
			->from(array('r' => 'pay_notify'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('r.order_id = ?',$this->callback_data['transaction_id']);
		  $count = $select->query()->fetchColumn();
		  if($count>0){
		    $this->ReplyNotify();//已经回调过
		  }
			$this->rows['record'] = $this->models['record']->createRow(array(
				'type' => 2,//微信支付
				'rid' => 0,
				'info' => $xml,
				'error_msg' => $error_msg,
				'rstatus' => 0,//状态
				'order_id' => $this->callback_data['transaction_id'],
				'oid' => $this->callback_data['out_trade_no'],
				'dateline' => SCRIPT_TIME,
			));
			$rid = $this->rows['record']->save();
			$this->rid = $rid;
		}

		if($notify_status != TRUE){
		  $this->ReplyNotify();//验证不通过
		}
	}
	
	public function notifyAction()
	{
		$orderId = $this->callback_data['out_trade_no'];
		$tradeNo =  $this->callback_data['transaction_id'];
		$outAccount = $this->callback_data['openid'];
		$totalFee = $this->callback_data['total_fee'];
		$this->rows['order'] = $this->models['order']->find($orderId)->current();
		$payAmount = $this->rows['order']->pay_amount*100;
		$payAmount = number_format($payAmount,2,'.','');
		if($payAmount!=$totalFee)
		{
			$this->record_update('价格不正确');
			exit ;
		}
		if (empty($this->rows['order'])) 
		{
			$this->record_update('订单不存在');
			exit ;
		}

		if ($this->rows['order']->status == Model_Order::WAIT_BUYER_PAY || $this->rows['order']->status == Model_Order::CANCLE) 
		{
			/* 更新订单 */
			$this->rows['order']->out_id = $tradeNo;
			$this->rows['order']->payment = 'wxweb';
			$this->rows['order']->out_account = $outAccount;
			$this->rows['order']->status = 1;
			$this->rows['order']->save();

			/* 业务处理 */
			$this->record_update('支付成功',1);
			afterPay($this->rows['order']->toArray());
			//$this->record_update('订单支付完成');
			//exit ;
		}else{
			$this->record_update('订单非未支付状态');
			exit ;
		}
	}

	public function onenotifyAction()
	{
		$orderId = $this->callback_data['out_trade_no'];
		$tradeNo =  $this->callback_data['transaction_id'];
		$outAccount = $this->callback_data['openid'];
		$totalFee = $this->callback_data['total_fee'];
		$this->rows['one_order'] = $this->models['one_order']->find($orderId)->current();
		$payAmount = $this->rows['one_order']->pay_amount*100;
		$payAmount = number_format($payAmount,2,'.','');
		if($payAmount!=$totalFee)
		{
			$this->record_update('价格不正确');
			exit ;
		}
		if (empty($this->rows['one_order']))
		{
			$this->record_update('订单不存在');
			exit ;
		}
	
		if ($this->rows['one_order']->status == Model_Order::WAIT_BUYER_PAY || $this->rows['one_order']->status == Model_Order::CANCLE)
		{
			/* 更新订单 */
			$this->rows['one_order']->out_id = $tradeNo;
			$this->rows['one_order']->payment = 'wxweb';
			$this->rows['one_order']->out_account = $outAccount;
			$this->rows['one_order']->status = 1;
			$this->rows['one_order']->save();
	
			/* 业务处理 */
			$this->record_update('支付成功',1);
			afterRechange($this->rows['one_order']->toArray());
			//$this->record_update('订单支付完成');
			//exit ;
		}else{
			$this->record_update('订单非未支付状态');
			exit ;
		}
	}


	/**
     +----------------------------------------------------------
	 * 记录错误节点
     +----------------------------------------------------------
	 * @access protected
     +----------------------------------------------------------
	 * @param string $error_msg 错误信息
	 * @param boolean $status 0：停止执行 1：继续执行
     +----------------------------------------------------------
	 * @return void
     +----------------------------------------------------------
	 * @throws ThinkExecption
     +----------------------------------------------------------
	 */
	protected function record_update($error_msg,$status=0)
	{
		$this->rows['record'] = $this->models['record']->find($this->rid)->current();
		$this->rows['record']->error_msg = $error_msg;
		$this->rows['record']->save();
		if($status==0){
			$this->ReplyNotify();		
		}
		return;
	}

	/** 
	* 返回信息
	*/ 
	protected function ReplyNotify()
	{
		$values['return_code'] = 'SUCCESS';
		$values['return_msg'] = 'OK';
		$returnXml = "<xml>";
		foreach ($values as $key=>$val){
			if (is_numeric($val)){
				$returnXml.="<".$key.">".$val."</".$key.">";
			}else{
				$returnXml.="<".$key."><![CDATA[".$val."]]></".$key.">";
			}
		}
		$returnXml.="</xml>";
		echo $returnXml;
		exit;
	}

}

//日志记录
function logger($log_content)
{
	 $max_size = 100000;
	 $log_filename = "log.xml";
	 if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
	 file_put_contents($log_filename, date('H:i:s')." ".$log_content."\r\n", FILE_APPEND);
}

?>