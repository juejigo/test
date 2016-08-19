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
		$adapter = new Zend_File_Transfer();
		
		/* 图片检验 */
		$adapter->addValidator('Extension',true,'jpg,jpeg,gif,png')
			->addValidator('Size',true,1024000 * $this->_config['max_size']);
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
		
		/* 上传处理 */
		
		$uploadConfig = Zend_Registry::get('config')->upload->toArray();
		
		if (is_file($destination)) 
		{
			if ($uploadConfig['cloud']['open'] == 1) 
			{
				if ($uploadConfig['cloud']['isServer'] == 0) 
				{
					$config = array(
					    'adapter'   => 'Zend_Http_Client_Adapter_Curl',
					    'curloptions' => array(CURLOPT_FOLLOWLOCATION => true),
					);
					$client = new Zend_Http_Client($uploadConfig['cloud']['serverIP'],$config);
					$client->setParameterPost('from',$this->_from);
					
					
					$client->setFileUpload($destination,'image');
					$response = $client->request('POST');
					$json = Zend_Json::decode($response->getBody());
					
					if ($json['errno'] != 0) 
					{
						return false;
					}
					
					$imageModel = new Model_Image();
					$imageRow = $imageModel->createRow(array(
						'member_id' => Zend_Auth::getInstance()->getIdentity()->id,
						'path' => $destination,
						'url' => $json['url'],
						'size' => $adapter->getFileSize(),
						'from' => $this->_from,
						'thumb' => $this->_config['thumb'],
						'thumb_width' => Zend_Serializer::serialize($this->_config['thumb_width'])
					));
					$imageRow->save();
					
					return $imageRow->toArray();
				}
				else 
				{
					/* 生成缩略图 */
					if ($this->_config['thumb'] == 1) 
					{
						$this->_thumbnail($destination);
					}
					
					$json = array();
					$json['errno'] = 0;
					$json['path'] = $destination;
					$json['url'] = URL_UPLOAD . $destination;
					$json['size'] = $adapter->getFileSize();
					$json['thumb'] = $this->_config['thumb'];
					$json['thumb_width'] = Zend_Serializer::serialize($this->_config['thumb_width']);
					
					echo Zend_Json::encode($json);
					exit;
				}
			}
			else 
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
				
				/* 生成缩略图 */
				if ($this->_config['thumb'] == 1) 
				{
					$this->_thumbnail($imageRow->id);
				}
				return $imageRow->toArray();
			}
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
	public function _thumbnail($image)
	{
		if (is_numeric($image)) 
		{
			$db = Zend_Registry::get('db');
			$image = $db->select()
				->from(array('i' => 'image'))
				->where('i.id = ?',$image)
				->query()
				->fetch();
			
			$thumbWidth = Zend_Serializer::unserialize($image['thumb_width']);
			if ($thumbWidth && is_array($thumbWidth)) 
			{
				if(extension_loaded('Imagick'))  
		        {
		        	$processor = new Core_Imagick($image['path']);
		        }
		        else if (function_exists('imagecreate')) 
		        {
		        	$processor = new Core_Gd($image['path']);
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
		else if (is_file($image)) 
		{
			if ($this->_config['thumb_width'] && is_array($this->_config['thumb_width'])) 
			{
				if(extension_loaded('Imagick'))  
		        {
		        	$processor = new Core_Imagick($image);
		        }
		        else if (function_exists('imagecreate')) 
		        {
		        	$processor = new Core_Gd($image);
		        }
		        else 
		        {
		        	return false;
		        }
		        
				foreach ($this->_config['thumb_width'] as $width) 
				{
					if (is_numeric($width)) 
					{
						$processor->thumbnail($width);
					}
				}
			}
		}
	}
}

?>