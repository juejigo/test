<?php

class Core_Imagick extends Zend_Controller_Action_Helper_Abstract 
{
	/**
	 *  @var imagick
	 */
	protected $_image = null;
	
	/**
	 *  @var array
	 */
	protected $_imageInfo = array();
	
	/**
	 *  @var string
	 */
	protected $_pathinfo = '';
	
	/**
	 *  @var string
	 */
	protected $_watermark = 'static/image/watermark.png';
	
	/**
	 *  构造方法
	 * 
	 *  设置图片
	 * 
	 *  @param string $image 图片路径
	 *  @return void
	 */
	public function __construct($image = '')
	{
		/*
		 *  设置图片
		 */
		
		if (!empty($image)) 
		{
			$this->setImage($image);
		}
	}
	
	/**
	 *  设置图片
	 * 
	 *  构造imagick,初始化图片信息
	 * 
	 *  @param string $image 图片路径
	 *  @return void
	 */
	public function setImage($image)
	{
		/* 构造 */
		$this->_image = $image;
		
		/* 尺寸信息 */
		$info = getimagesize($image);
		$this->_imageInfo = array('width' => $info[0],'height' => $info[1],'type' => $info[2]);
		
		/* 图片信息 */
		$pathinfo = pathinfo($image);
		$this->_pathinfo = array(
			'dir' => dirname($image),
			'filename' => $pathinfo['filename'],
			'ext' => strtolower($pathinfo['extension'])
		);
	}
	
	/**
	 *  缩略图
	 *  
	 *  目标路径,处理图片并保存,释放内存
	 * 
	 *  @param int $width 缩略图宽度
	 *  @param int $height 缩略图高度
	 *  @param boolean $sharpen 是否锐化
	 *  @return void
	 */
	public function thumbnail($width = 0,$height = 0,$sharpen = true,$dst = '')
	{
		/*
		 *  目标路径
		 */
		if (empty($dst)) 
		{
			$dst = "{$this->_image}.thumb.{$width}.{$this->_pathinfo['ext']}";
		}
		
		$dirs = dirname($dst);
			
		/* 目录不存在，则自动创建 */
		if (!is_dir($dirs)) 
		{
			mkdir($dirs,0777,true);
		}
		
		
		/*
		 *  处理图片并保存
		 */
		
		$imagick = new imagick($this->_image);
		$imagick->setCompressionQuality(100);
		if ($sharpen) 
		{
			$imagick->sharpenImage(5.0,5.0);
		}
		//$imagick->enhanceImage();
		$imagick->thumbnailImage($width,$height);
		$imagick->writeImage($dst);
		
		/*
		 *  释放内存
		 */
		
		$imagick->destroy();
		$imagick->clear();
		
		/* CMYK格式转RGB */
		$im = new Imagick($dst);
		$colorSpace = $im->getImageColorspace();
		if ($colorSpace == Imagick::COLORSPACE_CMYK) 
		{
			
			@exec("/usr/local/imagemagick/bin/convert -colorspace RGB {$dst} {$dst}");
		}
		$im->destroy();
		$im->clear();
	}
	
	/**
	 *  水印
	 * 
	 *  生成水印,释放内存
	 * 
	 *  @param string $position 水印位置
	 *  @return void
	 */
	public function watermark($position = 'rb')
	{
		/*
		 *  生成水印
		 */
		
		$imagick = new imagick($this->_image);
		$markImage = new Imagick($this->_watermark);
		$markWidth = $markImage->getImageWidth();
		$markHeight = $markImage->getImageHeight();
		$imageWidth = $imagick->getImageWidth();
		$imageHeight = $imagick->getImageHeight();
		
		/* 位置 */
		switch ($position)
		{
			case 'lt':
                $x = 10;
                $y = 10;
                break;
                
            case 'rt':
                $x = $imageWidth - $markWidth - 10;
                $y = 10;
                break;
                
            case 'rb':
                $x = $imageWidth - $markWidth - 10;
                $y = $imageHeight - $markHeight - 10;
                break;
                
            case 'lb':
                $x = 10;
                $y = $imageHeight - $markHeight - 10;
                break;
                
            default:
                $x = ($imageWidth - $markWidth - 10) / 2;
                $y = ($imageHeight - $markHeight - 10) / 2;
                break;
		}
		
		$imagick->compositeImage($markImage,Imagick::COMPOSITE_OVER,$x,$y);
		$imagick->writeImage();
		
		/*
		 *  释放内存
		 */
		
		$imagick->destroy();
		$imagick->clear();
	}
	
	/**
	 *  直接使用
	 */
	/*public function direct($image,$thumb = true,$width = 0,$height = 0,$sharpen = true,$watermark = true,$position = 'rb')
	{
		$this->setImage($image);
		if ($thumb) 
		{
			$this->thumbnail($width,$height,$sharpen);
		}
		if ($watermark) 
		{
			$this->watermark($position);
		}
	}*/
}

?>