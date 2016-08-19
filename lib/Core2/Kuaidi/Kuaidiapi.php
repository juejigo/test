<?php

class Core2_Kuaidi_Kuaidiapi
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
	 *  @var string
	 */
	private $_uid = '';
	
	/**
	 *  @var string
	 */
	private $_key = '';
	
	/**
	 *  @var array
	 */
	private $_companyCodeSwitcher = array(
		'zhaijisong' => 'zjs',
	);
	
	/**
	 *  构造
	 */
	public function __construct()
	{
		$this->_config = Zend_Registry::get('config')->kuaidi->kuaidiapi;
		$this->_uid = $this->_config->uid;
		$this->_key = $this->_config->key;
		
		$this->_client = new Zend_Http_Client(
			null,
			array(
				'maxredirects' => 10,
				'adapter' => 'Zend_Http_Client_Adapter_Curl',
				'curloptions' => array(
					CURLOPT_FOLLOWLOCATION => true))
		);
	}
	
	/**
	 *  获取物流数据
	 */
	public function getData($orderId,$companyNo)
	{
		$companyNo = array_key_exists($companyNo,$this->_companyCodeSwitcher) ? $this->_companyCodeSwitcher[$companyNo] : $companyNo;
		
		$this->_client->setUri('http://www.kuaidiapi.cn/rest');
		$this->_client->setParameterGet(array(
			'uid' => $this->_uid,
			'key' => $this->_key,
			'order' => $orderId,
			'id' => $companyNo
		));
		
		$response = $this->_client->request(Zend_Http_Client::GET);
		$result = $response->getBody();
		
		if (!$result = Zend_Json::decode($result)) 
		{
			return  array();
		}
		
		return array_reverse($result['data']);
	}
}

?>