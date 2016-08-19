<?php

class Utilityapi_VersionController extends Core2_Controller_Action_Api    
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
	}
	
	/**
	 *  返回版本信息
	 */
	public function lastAction() 
	{
		if (!form($this)) 
		{
			$this->json['errno'] = '1';
			$this->json['errmsg'] = $this->error->firstMessage();
			$this->_helper->json($this->json);
		}
		
		$this->json['errno'] = '0';
		
		if ($this->input->platform == 'android') 
		{
			$this->json['version'] = '17';
			$this->json['update_url'] = DOMAIN . "runtime/meixiejia_v1.2.5_17_{$this->input->channel}.apk";
		}
		else if ($this->input->platform == 'ios') 
		{
			$this->json['version'] = '0';
			$this->json['update_url'] = '';
		}
		$this->_helper->json($this->json);
	}
}

?>