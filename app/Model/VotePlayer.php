<?php
class Model_VotePlayer extends Zend_Db_Table_Abstract
{
	/**
	 *  @var int
	 */
	const NOPASS = '-1';
	
    /**
     *  @var string
     */
    protected $_name = 'vote_player';
    
    /**
     *  @var string
     */
    protected $_rowClass = 'Model_Row_VotePlayer';
    

}

?>