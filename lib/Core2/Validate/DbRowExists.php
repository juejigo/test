<?php

class Core2_Validate_DbRowExists extends Core_Validate_Abstract 
{
	const NO_EXISTS = 'noExists';
	
	/**
	 *  @string
	 */
	protected $_table = '';
	
	/**
	 *  @string
	 */
	protected $_field = '';
	
	/**
	 *  @string
	 */
	protected $_where = array();
	
	/**
	 *  @string
	 */
	protected $_allowEmpty = false;
	
	/**
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::NO_EXISTS => '数据不存在',
	);
	
	/**
	 *  构造
	 */
	public function __construct($options)
	{
		parent::__construct();
		
		if (!array_key_exists('table',$options) || !array_key_exists('field',$options))
		{
            require_once 'Zend/Validate/Exception.php';
            throw new Zend_Validate_Exception('Table or Schema option missing!');
        }
        
        if (empty($options['where'])) 
        {
        	$options['where'] = array();
        }
        
		$this->_table = $options['table'];
		$this->_field = $options['field'];
		$this->_where = $options['where'];
		
		if (isset($options['allowEmpty'])) 
		{
			$this->_allowEmpty = $options['allowEmpty'];
		}
	}
	
	/**
	 *  检验
	 * 
	 *  @param string $value
	 *  @return boolean
	 */
	public function isValid($value)
	{
		if ($value == 0 && $this->_allowEmpty) 
		{
			return true;
		}
		
		$select = $this->_db->select()
			->from($this->_table,array(new Zend_Db_Expr('COUNT(*)')))
			->where("{$this->_field} = ?",$value);
		foreach ($this->_where as $k => $v) 
		{
			$select->where($k,$v);
		}
		
		$count = $select->query()
			->fetchColumn();
		
		if ($count == 0) 
		{
			$this->_error(self::NO_EXISTS);
			return false;
		}
		
		return true;
	}
}

?>