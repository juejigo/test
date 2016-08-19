<?php

class Core2_Jpush
{
	/**
	 *  @var Zend_Http_Client
	 */
	protected $_client = null;
	
	/**
	 *  @var Zend_Config_Ini
	 */
	protected $_config = null;
	
	/**
	 *  @var Array
	 */
	protected $_options = array();
	
	/**
	 *  构造
	 */
	public function __construct()
	{
		$this->_client = new Zend_Http_Client();
		$this->_config = Zend_Registry::get('config')->jpush;
	}
	
	/**
	 *  推送
	 * 
	 *  @param string $json
	 *  @return string
	 */
	public function push($options)
	{
		$data = Zend_Json::encode($options);
		$this->_client->setUri('https://api.jpush.cn/v3/push');
		$this->_client->setRawData($data,'application/json');
		$this->_client->setAuth($this->_config->appKey,$this->_config->masterSecret);
		$response = $this->_client->request('POST');
		$result = $response->getBody();
		$json = Zend_Json::decode($result);

		if (isset($json['msg_id']))
		{
			return true;
		}
		return false;
	}
	
	/**
	 *  定时推送
	 */
	public function timePush($options,array $timePushArray = array())
	{
		$timePushArray['push']=$options;
		$data = Zend_Json::encode($timePushArray);
		$this->_client->setUri('https://api.jpush.cn/v3/schedules');
		$this->_client->setRawData($data,'application/json');
		$this->_client->setAuth($this->_config->appKey,$this->_config->masterSecret);
		$response = $this->_client->request('POST');
		$result = $response->getBody();
		$json = Zend_Json::decode($result);

		if (isset($json['msg_id']))
		{
			return true;
		}
		
		return false;
	}
	
	/**
	 *  多条推送
	 */
	public function multiPush(array $audienceMemberId=array(),array $platform =array(),$message,$title = '',array $options = array(),$timePush)
	{
		$db = Zend_Registry::get('db');
		
		if (empty($audienceMemberId) && empty($platform))
		{
			return false;
		}
		
		if (!empty($audienceMemberId))
		{
			$registration = $db->select()
			->from(array('r' => 'app_registrationid'))
			->where('r.member_id in(?)',$audienceMemberId)
			->where('r.platform in(?)',$platform)
			->query()
			->fetchAll();
			
			$registrationid = array();
			foreach($registration as $v)
			{
				$registrationid[] = $v['registrationid'];
			}
				
			$this->_options['audience']['registration_id'] = $registrationid;
			$this->_options['platform'] = $platform;
		}
		
		if (empty($registration))
		{
			return false;
		}
		
		$this->_options['message'] = array(
				'title' => $title,
				'msg_content' => $message,
				'content_type' => 'text',
		);
		
		$this->_options['notification'] = array(
				'alert' => $message,
		);
		
		if (isset($options['extras']))
		{
			$this->_options['message']['extras'] = $options['extras'];
			$this->_options['notification']['ios']['extras'] = $options['extras'];
			$this->_options['notification']['android']['extras'] = $options['extras'];
		}
		
		if (isset($options['sendno']))
		{
			$this->_options['options']['sendno'] = $options['sendno'];
		}
		
		$this->_options['options']['apns_production'] = $this->_config->apns == 1 ? true : false;
		
		if(isset($timePush))
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
			return $this->timePush($this->_options,$timePushArray);
		}
		return $this->push($this->_options);
	}
	
	/**
	 *  单条推送
	 */
	public function singlePush(array $audience,$message,$title = '',array $options = array(),$timePush)
	{
		$db = Zend_Registry::get('db');
		
		if (!isset($audience['member_id']) && !isset($audience['registrationid']))
		{
			return false;
		}
		
		if (isset($audience['registrationid']))
		{
			$registration = $db->select()
				->from(array('r' => 'app_registrationid'))
				->where('r.registrationid = ?',$audience['registrationid'])
				->query()
				->fetch();
			
			$this->_options['audience']['registration_id'] = array($registration['registrationid']);
			$this->_options['platform'] = array($registration['platform']);
		}
		else if (isset($audience['member_id'])) 
		{
			$registration = $db->select()
				->from(array('r' => 'app_registrationid'))
				->where('r.member_id = ?',$audience['member_id'])
				->query()
				->fetch();
			
			$this->_options['audience']['registration_id'] = array($registration['registrationid']);
			$this->_options['platform'] = array($registration['platform']);
		}
		
		if (empty($registration)) 
		{
			return false;
		}
		
		//$this->_options['audience'] = 'all';
		//$this->_options['platform'] = 'all';
		
		$this->_options['message'] = array(
			'title' => $title,
			'msg_content' => $message,
			'content_type' => 'text',
		);
		
		$this->_options['notification'] = array(
			'alert' => $message,
		);
		
		if (isset($options['extras'])) 
        {
        	$this->_options['message']['extras'] = $options['extras'];
        	$this->_options['notification']['ios']['extras'] = $options['extras'];
        	$this->_options['notification']['android']['extras'] = $options['extras'];
        }
        
        if (isset($options['sendno'])) 
        {
        	$this->_options['options']['sendno'] = $options['sendno'];
        }
        
        $this->_options['options']['apns_production'] = $this->_config->apns == 1 ? true : false;
        
        if(isset($timePush))
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
        	return $this->timePush($this->_options,$timePushArray);
        }
        return $this->push($this->_options);
	}
}

?>