<?php

class Model_Row_VoteComment extends Zend_Db_Table_Row_Abstract 
{
    /**
     *  插入前
     *
     *  @return void
     */
    protected function _insert()
    {
        /* 创建时间 */
    
        $this->__set('dateline',SCRIPT_TIME);
    
        /* 是否需要审核*/
        
		if (!isset($this->_data['status']))
		{
			$commentAuth = $this->_table->getAdapter()->select()
				->from(array('v' => 'vote'),array('comment_auth'))
				->where('v.id =?',$this->_data['vote_id'])
				->query()
				->fetchColumn();
			
			if ($commentAuth == 0)
			{
				$this->__set('status',1);
			}
			else
			{
				$this->__set('status',0);
			}
		}
    }
}

?>