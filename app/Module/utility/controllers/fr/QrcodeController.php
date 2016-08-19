<?php

class  Utility_QrcodeController extends Core2_Controller_Action_Fr 
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
	}
	
	/**
	 *  首页
	 */
	public function indexAction()
	{
		/* 检验传值 */
		if (!params($this)) 
		{
			$this->_helper->notice('页面错误',$this->error->firstMessage(),'error_1',array());
		}
		
		$content = urldecode($this->paramInput->content);
		
		include 'lib/api/phpqrcode/phpqrcode.php';
		QRcode::png($content,false,QR_ECLEVEL_L,7);
		exit;
	}
	
	/**
	 *  员工二维码
	 */
	public function promoterAction()
	{
		if ($this->_request->isPost())
		{
			if (!form($this))
			{
				echo $this->error->firstMessage();
				exit;
			}
			
			$memberId = $this->_db->select()
				->from(array('m' => 'member'),array('id'))
				->where('m.account = ?',$this->_request->account)
				->query()
				->fetchColumn();
			$r = createR($memberId);
			$url = DOMAIN . "user/account/register?r={$r}&register_from=23";
			$urlEncode = urlencode($url);
			
			$this->view->qrcodeUrl = DOMAIN . "utility/qrcode?content={$urlEncode}";
		}
	}
}

?>