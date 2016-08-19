<?php

class Model_Order extends Zend_Db_Table_Abstract 
{
	/**
	 *  @var string
	 */
	protected $_name = 'order';
	
	/**
	 *  @var string
	 */
	protected $_rowClass = 'Model_Row_Order';
	
	/**
	 *  @var int
	 */
	const CANCLE = '-1';
	
	/**
	 *  @var int
	 */
	const WAIT_BUYER_PAY = '0';
	
	/**
	 *  @var int
	 */
	const WAIT_SELLER_SEND_GOODS = '1';
	
	/**
	 *  @var int
	 */
	const WAIT_BUYER_CONFIRM_GOODS = '2';
	
	/**
	 *  @var int
	 */
	const TRADE_FINISHED = '3';
	
	/**
	 *  @var int
	 */
	const WAIT_SELLER_AGREE = '10';
	
	/**
	 *  @var int
	 */
	const WAIT_BUYER_RETURN_GOODS = '11';
	
	/**
	 *  @var int
	 */
	const WAIT_SELLER_CONFIRM_GOODS = '12';
	
	/**
	 *  @var int
	 */
	const REFUND_SUCCESS = '13';
	
	/**
	 *  @var int
	 */
	const SELLER_REFUSE_BUYER = '14';
	
	/**
	 * @var int
	 */
	const DISPLAY_DELETE = '0';
	
	/**
	 *  生成订单号
	 */
	public function createId()
	{
        $y = date('Y',SCRIPT_TIME);
        $z = date('z',SCRIPT_TIME);
        $id = $y . str_pad($z,3,'0',STR_PAD_LEFT) . str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT);

        $exists = $this->fetchRow(
        	$this->select()
        		->where('id = ?',$id)
        );
        if (empty($exists))
        {
            return $id;
        }

        return $this->createId();
	}
	
	/**
	 *  生成消费码
	 */
	public function createSn()
	{
		$stack = array(0,1,2,3,4,5,6,7,8,9);
		shuffle($stack);
		$sn = '';
		for ($i = 0;$i < 16;$i ++)
		{
			$sn .= $stack[$i];
		}

        $exists = $this->getAdapter()
        	->select()
        	->from(array('i' => 'order_item'))
        	->where('i.sn = ?',$sn)
        	->query()
        	->fetch();
        if (empty($exists))
        {
            return $sn;
        }

        return $this->createSn();
	}
}

?>