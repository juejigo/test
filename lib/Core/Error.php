<?php

class Core_Error
{
	/**
	 *  @var array
	 */
	protected $_errors = array();
	
	/**
	 *  添加错误
	 * 
	 *  @param string $key 消息键值
	 *  @param string $message 消息
	 *  @return void
	 */
	public function add($key,$message)
	{
		if (array_key_exists($key,$this->_errors)) 
		{
			$this->_errors[$key][] = $message;
		}
		else 
		{
			$this->_errors[$key] = array($message);
		}
	}
	
	/**
	 *  重置错误
	 * 
	 *  @param string $key 消息键值
	 *  @param string $message 消息
	 *  @return void
	 */
	public function set($key,$message)
	{
		$this->_errors[$key] = array($message);
	}
	
	/**
	 *  获取错误
	 * 
	 *  @param string $key 消息键值
	 *  @param boolean $first 是否只返回第一条
	 *  @return string|array
	 */
	public function get($key,$first = true)
	{
		if (empty($this->_errors[$key])) 
		{
			return null;
		}
		$keys = array_keys($this->_errors[$key]);
		return $first ? $this->_errors[$key][$keys[0]] : $this->_errors[$key];
	}
	
	/**
	 *  获取所有错误信息中的第一条信息
	 * 
	 *  @return string
	 */
	public function firstMessage()
	{
		if (empty($this->_errors)) 
		{
			return false;
		}
		
		$messages = array_shift($this->_errors);
		return array_shift($messages);
	}
	
	/**
	 *  导入信息
	 * 
	 *  @return void
	 */
	public function import(array $errors)
	{
		$this->_errors = array_merge($this->_errors,$errors);
	}
	
	/**
	 *  导出全部信息
	 */
	public function getAll()
	{
		return $this->_errors;
	}
	
	/**
	 *  是否有错误
	 * 
	 *  @return boolean
	 */
	public function hasError()
	{
		return !empty($this->_errors);
	}
	
	/**
	 *  魔法方法
	 * 
	 *  @param string $key 消息键值
	 *  @return string
	 */
	public function __get($key)
	{
		return $this->get($key);
	}
	
	/**
	 *  魔法方法
	 * 
	 *  @param string $key 消息键值
	 *  @param string $value 消息
	 *  @return void
	 */
	public function __set($key,$value)
	{
		$this->add($key,$value);
	}
}

?>