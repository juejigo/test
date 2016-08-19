<?php

class Core2_MobileLocation_Caifutong extends Core2_MobileLocation_Abstract 
{
	/**
	 *  获取城市和省份
	 * 
	 *  @param $mobile 手机号码
	 */
	public function location($mobile)
	{
		$this->_client->setUri('http://life.tenpay.com/cgi-bin/mobile/MobileQueryAttribution.cgi');
		$this->_client->setParameterGet(array(
			'chgmobile' => $mobile
		));
		$response = $this->_client->request(Zend_Http_Client::GET);
		$result = $response->getBody();
		
		$dom = new Domdocument('1.0','utf-8');
	    $dom->loadXML($result);
		
	    $retCode = $dom->getElementsByTagName('retcode')->item(0)->nodeValue;
	    
		if($retCode != 0)
		{
			return false;
		}
		
		$province = $dom->getElementsByTagName('province')->item(0)->nodeValue;
	 	$city =trim($dom->getElementsByTagName('city')->item(0)->nodeValue).'市';
	    return $this->getRegionId($province,$city);
	}
}

?>