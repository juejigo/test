<?php

class Couponapi_CouponController extends Core2_Controller_Action_Api   
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
	}
	
	/**
	 *  优惠券列表
	 */
	public function listAction()
	{
		/* 获取优惠券 */
		
		$couponList = $this->_db->select()
			->from(array('u' => 'coupon_user'),array('coupon_user_id' => 'id','deadline','get_time'))
			->joinLeft(array('c' => 'coupon'),'c.id = u.coupon_id',array('coupon_name','value'))
			->where('u.member_id = ?',$this->_user->id)
			->where('u.status = ?',1)
			->order('u.get_time DESC')
			->query()
			->fetchAll();
		$this->json['coupon_list'] = $couponList;
		
		$this->json['errno'] = '0';
		$this->_helper->json($this->json);
	}
}

?>