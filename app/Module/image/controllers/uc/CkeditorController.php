<?php

class Imageuc_CkeditorController extends Core2_Controller_Action_Uc 
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
	}
	
	/**
	 *  上传
	 */
	public function uploadAction()
	{
		if (!params($this)) 
		{
			$this->mkhtml(1,'',$this->error->firstMessage());
		}
		
		$image = new Core2_Image();
		if (!$row = $image->upload('upload','editor'))
		{
			$this->mkhtml(1,'','仅支持jpg,jpeg,gif,png格式，大小不能超过1M');
		}
		

		$this->mkhtml($this->paramInput->CKEditorFuncNum,thumbpath($row['url']),'');
	}
	
	/**
	 *  输出
	 */
	function mkhtml($fn,$fileurl,$message)
	{
		$str = '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$fn.', \''.$fileurl.'\', \''.$message.'\');</script>';
		exit($str);
	}
}

?>