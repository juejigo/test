<?php

class Core2_Image
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
	public function __construct($from)
	{
		$this->_from = $from;
		$this->_config();
	}
	
	/**
	 *  上传图片
	 */
	public function upload($file)
	{
		/* 图片检验 */
		
		$adapter = new Zend_File_Transfer();
		$adapter->addValidator('Extension',true,'jpg,jpeg,gif,png,JPG,JPEG,GIF,PNG')
			->addValidator('Size',true,1024000 * $this->_config['max_size']);
		if (!$adapter->isValid($file)) 
		{
			return false;
		}
		
		/* 生成图片地址 */
		
		$destination = $this->_destination($file);
		$dirs = dirname($destination);
	    if (!is_dir($dirs))
	    {
	    	mkdir($dirs,0777,true);
	    }
		
	    /* 保存图片 */
	    
		$adapter->addFilter('Rename',$destination,$file);
		$adapter->receive($file);
		
		/* 上传到图片服务器 */
		
		$client = new Zend_Http_Client('http://upload.meixiejia.net/imageuc/image/upload');
		$client->setParameterPost('from',$this->_from);
		$client->setFileUpload($destination,'image');
		$response = $client->request('POST');
		
		/*if (is_file(localpath($destination))) 
		{
			$imageModel = new Model_Image();
			$imageRow = $imageModel->createRow(array(
				'member_id' => Zend_Auth::getInstance()->getIdentity()->id,
				'path' => $destination,
				'url' => URL_UPLOAD . $destination,
				'size' => $adapter->getFileSize(),
				'from' => $this->_from,
				'thumb' => $this->_config['thumb'],
				'thumb_width' => Zend_Serializer::serialize($this->_config['thumb_width'])
			));
			$imageRow->save();
			
			// 生成缩略图
			if ($this->_config['thumb'] == 1) 
			{
				$this->_thumbnail($imageRow->id);
			}
			
			return $imageRow->toArray();
		}*/
		
		return false;
	}
	
	/**
	 *  二进制
	 */
	public function binary()
	{
		/* 获取二进制内容 */
		
		$content = empty($GLOBALS['HTTP_RAW_POST_DATA']) ? file_get_contents('php://input') : empty($GLOBALS['HTTP_RAW_POST_DATA']);
        if(empty($content))
        {
        	echo '无二进制内容';
            exit;
        }
        
        /* 生成图片地址 */
        
        $destination = $this->_destination($file);
		$dirs = dirname($destination);
	    if (!is_dir($dirs))
	    {
	    	mkdir($dirs,0777,true);
	    }
        
	    /* 保存图片 */
	    
        $ret = file_put_contents($destination,$content,true);
		
		if (is_file(localpath($destination))) 
		{
			$imageModel = new Model_Image();
			$imageRow = $imageModel->createRow(array(
				'member_id' => Zend_Auth::getInstance()->getIdentity()->id,
				'path' => $destination,
				'url' => URL_UPLOAD . $destination,
				'size' => $adapter->getFileSize(),
				'from' => $this->_from,
				'thumb' => $this->_config['thumb'],
				'thumb_width' => Zend_Serializer::serialize($this->_config['thumb_width'])
			));
			$imageRow->save();
			
			// 生成缩略图
			if ($this->_config['thumb'] == 1) 
			{
				$this->_thumbnail($imageRow->id);
			}
			
			return $imageRow->toArray();
		}
		
		return false;
	}
	
	/**
	 *  获取配置
	 */
	public function _config()
	{
		$config = Zend_Registry::get('config')->image->toArray();
		$this->_config['max_size'] = !empty($config[$this->_from]['maxSize']) ? $config[$this->_from]['maxSize'] : '1';
		$this->_config['thumb'] = !empty($config[$this->_from]['thumbWidth']) ? '1' : '0';
		$this->_config['thumb_width'] = !empty($config[$this->_from]['thumbWidth']) ? $config[$this->_from]['thumbWidth'] : array();
	}
	
	/**
	 *  生成本地地址
	 */
	public function _destination($file)
	{
		$ext = strtolower(strrchr($_FILES[$file]['name'],'.'));
		$destination = 'static/data/image/' . date('Y/m/d/',time()) . date('His',time()) . mt_rand(1000,100000) . "{$ext}";
		
		return $destination;
	}
	
	/**
	 *  生成缩略图
	 */
	public function _thumbnail($imageId)
	{
		$db = Zend_Registry::get('db');
		$image = $db->select()
			->from(array('i' => 'image'))
			->where('i.id = ?',$imageId)
			->query()
			->fetch();
		
		$thumbWidth = Zend_Serializer::unserialize($image['thumb_width']);
		if ($thumbWidth && is_array($thumbWidth)) 
		{
			if(extension_loaded('Imagick'))  
	        {
	        	$processor = new Core_Imagick(localpath($image['path']));
	        }
	        else if (function_exists('imagecreate')) 
	        {
	        	$processor = new Core_Gd(localpath($image['path']));
	        }
	        else 
	        {
	        	return false;
	        }
	        
			foreach ($thumbWidth as $width) 
			{
				if (is_numeric($width)) 
				{
					$processor->thumbnail($width);
				}
			}
		}
	}
}

?>