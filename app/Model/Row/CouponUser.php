<?php

class Model_Row_CouponUser extends Zend_Db_Table_Row_Abstract 
{
	/**
	 *  数据插入前处理
	 * 
	 *  @return void
	 */
	protected function _insert()
	{
		/* 过期时间 */
		
		$couponModel = new Model_Coupon();
		$couponRow = $couponModel->find($this->_data['coupon_id'])->current();
		$expiry = Zend_Serializer::unserialize($couponRow->expiry);
		
		// 获得后有效时间过期
		if ($expiry['type'] == 0) 
		{
			$this->__set('deadline',strtotime("+{$expiry['months']} months"));
		}
		// 固定时间过期
		else if ($expiry['type'] == 1) 
		{
			$this->__set('deadline',$expiry['date']);
		}
		
		/* 优惠券累计张数 */
		
		$couponRow->gen_num += 1;
		$couponRow->save();
		
		/* 备注 */
		
		$this->__set('memo',$couponRow->memo);
		
		/* 获得时间 */
		
		$this->__set('get_time',SCRIPT_TIME);
		
		/* 状态 */
		
		$this->__set('status',1);
	}
}

?>