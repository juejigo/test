<?php
class Model_EnvelopeSchedule extends Zend_Db_Table_Abstract
{
    /**
     *  @var string
     */
    protected $_name = 'envelope_schedule';
    
    
    
    /**
     *  更新后
     */
    protected function _postUpdate()
    {
        if (in_array('send_money',$this->_modifiedFields) && $this->_data['send_money'] == $this->_data['total_money'] )
        {
            $this->__set('send_nexttime',0);
        }
    }
    
    
}