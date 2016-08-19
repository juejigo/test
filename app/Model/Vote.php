<?php
class Model_Vote extends Zend_Db_Table_Abstract
{
    /**
     *  @var string
     */
    protected $_name = 'vote';

    /**
     *  @var string
     */
    protected $_rowClass = 'Model_Row_Vote';
    
}

?>