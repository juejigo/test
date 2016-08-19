<?php

class Model_Row_VotePlayer extends Zend_Db_Table_Row_Abstract 
{
    /**
     *  插入前
     *
     *  @return void
     */
    protected function _insert()
    {
        /* 创建时间 */
    
        $this->__set('join_time',SCRIPT_TIME);
        
        /* 是否需要审核*/
        
		if (!isset($this->_data['status']))
		{
			$playerAuth = $this->_table->getAdapter()->select()
				->from(array('v' => 'vote'),array('player_auth'))
				->where('v.id =?',$this->_data['vote_id'])
				->query()
				->fetchColumn();
			
			if ($playerAuth == 0)
			{
				$this->__set('status',1);
			}
			else
			{
				$this->__set('status',0);
			}
		}
		
		/* 得票数初始化*/
		
		if(!isset($this->_data['vote_num']))
		{
			$this->__set('vote_num',0);
		}
		
		/* 选手编号*/
		
		$playerNum = $this->_table->getAdapter()->select()
			->from(array('p' => 'vote_player'),array(new Zend_Db_Expr('MAX(player_num)')))
			->where('p.vote_id = ?',$this->_data['vote_id'])
			->query()
			->fetchColumn();
		
		$this->__set('player_num',$playerNum+1);
    }
    
    /**
     *  更新后
     *
     *  @return void
     */
    protected function _postUpdate()
    {
    	if (in_array('status',$this->_modifiedFields) && $this->_data['status'] != $this->_cleanData['status'] && $this->_data['status'] == Model_VotePlayer::NOPASS)
    	{
    		$activityName=$this->_table->getAdapter()->select()
    		->from(array('v' => 'vote'),array('vote_name'))
    		->where('v.id =?',$this->_data['vote_id'])
    		->query()
    		->fetchColumn();
    
    		$sms = new Core_Sms();
    		$content = sprintf('【众游网络】您参与的投票"%s"，资料审核未通过，请登录公众号重新发布。',$activityName);
    		$result = $sms->send($this->_data['phone'],$content);
    	}
    }
}

?>
