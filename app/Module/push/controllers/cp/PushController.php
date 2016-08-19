<?php

class Pushcp_PushController extends Core2_Controller_Action_Cp  
{
	/**
	 *  初始化
	 */
	public function init()
	{
		parent::init();
		
		$this->models['app_registrationid'] = new Model_AppRegistrationid();
		$this->models['member'] = new Model_Member();
	}
	
	/**
	 *  首页
	 */
	public function indexAction()
	{
		$this->_redirect('/pushcp/push/jpush');
	}

	/**
	 *  推送
	 */
	public function jpushAction()
	{
	}
	
	/**
	 *  推送
	 */
	public function ajaxAction()
	{
		if (!$this->_request->isXmlHttpRequest())
		{
			exit ;
		}
	
		$op = $this->_request->getQuery('op','');
		$json = array();
		$this->_helper->viewRenderer->setNoRender();
		
		if (!ajax($this))
		{
			$json['flag'] = "error";
			$json['msg'] = $this->error->firstMessage();
			$this->_helper->json($json);
		}
	
		switch ($op)
		{
			case 'jpush':

			 	$push = new Core2_Jpush();
			 	$userId=explode(',',$this->input->user_id);
			 	$message= $this->input->content;
			 	$title = $this->input->title;
			 	
			 	//推送类型
			 	if($this->input->type=='product')
			 	{
			 		$typeId = $this->input->product_id; 
			 	}
			 	else 
			 	{
			 		$typeId = $this->input->order_id;
			 	}
			 	
			 	$options['extras']=array('type'=>$this->input->type,'id'=>$typeId);
			 	
			 	//定时推送
			 	if($this->input->send_time != '')
			 	{
		 			$timePush=date('Y-m-d H:i:s',strtotime($this->input->send_time));
			 	}

		 		/* 单条推送 */
			 	
		 		if($this->input->target =='one' && count($userId) ==1)
		 		{
		 			$audience['member_id']=$userId;
		 			$push -> singlePush($audience,$message,$title,$options,$timePush);
		 		}
		 		
		 		/* 多条推送 */
		 		
		 		if($this->input->target =='one' && count($userId) >1)
		 		{
		 			if(isset($this->input->android))
		 			{
		 				$platform=array('android');
		 			}
		 			if(isset($this->input->ios))
		 			{
		 				$platform[]='ios';
		 			}
		 			$push->multiPush($userId,$platform,$message,$title,$options,$timePush);
		 		}
		 		
		 		/* 全部推送 */
		 		
		 		if($this->input->target =='all')
		 		{
		 			$options = array(
		 					'platform' => 'all',
		 					'audience' => 'all',
		 			);

		 	 		$options['message'] = array(
		 					'title' => $title,
		 					'msg_content' => $message,
		 					'content_type' => 'text',
		 			);
		 			
		 			$options['notification'] = array(
		 					'alert' => $message,
		 			);
		 			
		 			$type = $this->input->type;
		 			$extrasArray = array('type' => $type,'id' => $typeId);
		 			
	 				$options['message']['extras'] = $extrasArray;
		 			$options['notification']['android']['extras']= $extrasArray;
	 				$options['notification']['ios']['extras'] = $extrasArray;

	 				$this->_config = Zend_Registry::get('config')->jpush;
		 			$options['apns_production'] = $this->_config->apns == 1 ? true : false;

		 			if($this->send_type == 'timer')
		 			{
		 				$timePushArray = array();
		 				$timePushArray = array(
		 						'name' => '定时发送',
		 						'enabled' => true,
		 						'trigger' => array(
		 								'single' => array(
		 										'time' => $timePush
		 								),
		 						),
		 				);
		 				$push->timePush($options,$timePushArray);
		 			}
		 			$push->push($options);
		 		}
			 	
			 	$json['flag'] = 'success';
			 	$json['msg'] = "发送成功!";
			 	
			 	/* 确认用户 */
			 	
			 	if($this->input->target != 'all')
			 	{
				 	$userIsTrue = $this->_db->select()
							->from(array('m' => 'member'),array(new Zend_Db_Expr('COUNT(*)')))
							->where('m.id in(?)',$userId)
							->where('m.status = ?',1)
							->query()
							->fetchColumn();
				 	if($userIsTrue != count($userId))
				 	{
				 		$json['msg'] = "发送成功! 部分用户错误！";
				 	}
			 	}
			 	
			 	$this->_helper->json($json);
				break;
				
				default :
					break ;
			}
			$this->_helper->json($json);
	}

}

?>