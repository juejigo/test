<?php
class JSSDK {
  private $appId;
  private $appSecret;
  private $cacheDir;
  private $url;

  public function __construct($appId, $appSecret,$url) {
    $this->appId = $appId;
    $this->appSecret = $appSecret;
	$config = Zend_Registry::get('config');
	$this->cacheDir = $config->cache->backend->options->cache_dir;
	//echo $this->cacheDir;exit;
	if($url)$this->url = $url;
  }

  public function getSignPackage() {
    $jsapiTicket = $this->getJsApiTicket();
    $url = $this->url ? $this->url : "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $timestamp = time();
    $nonceStr = $this->createNonceStr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

    $signature = sha1($string);

    $signPackage = array(
      "appId"     => $this->appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage; 
  }

  private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

  private function getJsApiTicket() {
	//$firstLetter = $_GET['token'];
	$firstLetter = $this->cacheDir;
	$path = $firstLetter.'/jsapi_ticket.json';
	$status = file_exists($path);
	if($status){
	  $data = json_decode(file_get_contents($path),1);
	}
    if (!$status || $data['expire_time'] < time()) {
      $accessToken = $this->getAccessToken();
      $url = "http://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
      $res = json_decode($this->httpGet($url));
	  //$res = json_decode(file_get_contents($url));
      $ticket = $res->ticket;
      if ($ticket) {
        $data['expire_time'] = time() + 7000;
        $data['jsapi_ticket'] = $ticket;
		file_put_contents($path,json_encode($data));
		//cookie('wx_access_token',json_encode($data),7200);
        /*$fp = fopen("jsapi_ticket.json", "w");
        fwrite($fp, json_encode($data));
        fclose($fp);*/
      }
    } else {
      $ticket = $data['jsapi_ticket'];
    }
    return $ticket;
  }

  private function getAccessToken() {
	//$firstLetter = $_GET['token'];
	$cacheDir = $this->cacheDir;
	$path = $cacheDir.'/access_token.json';
	$status = file_exists($path);
	if($status){
	  $data = json_decode(file_get_contents($path),1);
	}
    if (!$status || $data['expire_time'] < time()) {
      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
	  $res = json_decode($this->httpGet($url));
      $access_token = $res->access_token;
      if ($access_token) {
        $data['expire_time'] = time() + 7000;
        $data['access_token'] = $access_token;
		file_put_contents($path,json_encode($data));
		//cookie('wx_access_token',json_encode($data),7200);
        /*$fp = fopen("access_token.json", "w");
        fwrite($fp, json_encode($data));
        fclose($fp);*/
      }
    } else {
      $access_token = $data['access_token'];
    }
    return $access_token;
  }

  private function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
  }

}

