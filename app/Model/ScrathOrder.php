<?php

class Model_ScrathOrder extends Zend_Db_Table_Abstract
{
    /**
     *  @var string
     */
    protected $_name = 'scrath_order';
    
    /**
     *  @var int
     */
    const PAYMENT_SUCCESS = '1';
    
    
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
    
    
}