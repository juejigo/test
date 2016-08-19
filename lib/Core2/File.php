<?php

class Core2_File
{
	/**
	 *  @string
	 */
	protected $_config = array();
	
	/**
	 *  @string
	 */
	protected $_from = '';
	
	/**
	 *  构造
	 */
	public function __construct()
	{
	}
	
	/**
	 *  上传文件
	 */
	public function upload($file)
	{
		$adapter = new Zend_File_Transfer();
		
		/* 文件检验 */
		$adapter->addValidator('Extension',true,'doc,txt,docx,xlsx,xls')
			->addValidator('Size',true,10024000);

		if (!$adapter->isValid($file)) 
		{
			return false;
		}
		
		$destination = $this->_destination($file);

		
		$dirs = dirname($destination);
	    if (!is_dir($dirs))
	    {
	    	mkdir($dirs,0777,true);
	    }
		
		$adapter->addFilter('Rename',$destination,$file);
		$adapter->receive($file);
		if ($adapter) 
		{
            return $destination;
		}
		
		return false;
	}

	/**
	 *  生成本地地址
	 */
	public function _destination($file)
	{
		$ext = strtolower(strrchr($_FILES[$file]['name'],'.'));
		$destination = 'static/data/file/' . date('Y/m/d/',time()) . date('His',time()) . mt_rand(1000,100000) . "{$ext}";
		
		return $destination;
	}

}

?>