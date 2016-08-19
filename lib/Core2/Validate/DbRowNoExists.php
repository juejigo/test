<?php

class Core2_Validate_DbRowNoExists extends Core_Validate_Abstract 
{
	const EXISTS = 'exists';
	
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
	 *  @var array
	 */
	protected $_messageTemplates = array(
		self::EXISTS => '数据已存在'
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
	}
	
	/**
	 *  检验
	 * 
	 *  @param string $value
	 *  @return boolean
	 */
	public function isValid($value)
	{
		$select = $this->_db->select()
			->from($this->_table,array(new Zend_Db_Expr('COUNT(*)')))
			->where("{$this->_field} = ?",$value);
		foreach ($this->_where as $k => $v) 
		{
			$select->where($k,$v);
		}
		$count = $select->query()
			->fetchColumn();
		
		if ($count > 0) 
		{
			$this->_error(self::EXISTS);
			return false;
		}
		
		return true;
	}
}

?>