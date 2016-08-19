<?php

class Imageuc_KindeditorController extends Core2_Controller_Action_Uc 
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
		$json = array();
		
		$image = new Core2_Image('editor');
		if (!$row = $image->upload('imgFile'))
		{
			$json['error'] = 1;
			$json['message'] = '仅支持jpg,jpeg,gif,png格式，图片大小不要超过1M';
			$this->_helper->json($json);
		}
		
		if (!file_exists(localpath($row['path']))) 
		{
			$json['error'] = 1;
			$json['message'] = '图片无法保存';
			$this->_helper->json($json);
		}
		
		$json['error'] = 0;
		$json['url'] = $row['url'];
		$this->_helper->json($json);
	}
}

?>