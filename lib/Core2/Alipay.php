<?php

class Core2_Alipay 
{
	/**
	 *  @var string
	 */
	public $partner = '';
	
	/**
	 *  @var string
	 */
	public $key = '';
	
	/**
	 *  @var string
	 */
	public $sellerEmail = '';
	
	/**
	 *  @var string
	 */
	public $mainName = '';
	
	/**
	 *  @var string
	 */
	public $signType = '';
	
	/**
	 *  @var string
	 */
	public $inputCharset = '';
	
	/**
	 *  @var string
	 */
	public $transport = '';
	
	/**
	 *  @var string
	 */
	public $gateway = '';
	
	/**
	 *  @var string
	 */
	public $mysign = '';
	
	/**
	 *  构造
	 */
	public function __construct()
	{
		$config = Zend_Registry::get('config');
		
		$this->partner = $config->pay->alipay->partner;
		$this->key = $config->pay->alipay->key;
		$this->sellerEmail = $config->pay->alipay->sellerEmail;
		$this->mainName = $config->pay->alipay->mainName;
		$this->signType = $config->pay->alipay->signType;
		$this->inputCharset = $config->pay->alipay->inputCharset;
		$this->transport = $config->pay->alipay->transport;
		
		$this->gateway = ($this->transport == 'https') ? 'https://www.alipay.com/cooperate/gateway.do?' : 'http://notify.alipay.com/trade/notify_query.do?';
	}
	
	/**
	 *  异步验证
	 */
	public function notifyVerify()
	{
		if (empty($_POST)) 
		{
			return false;
		}
		
		$url = ($this->transport == 'https') ? "{$this->gateway}service=notify_verify&partner={$this->partner}&notify_id={$_POST['notify_id']}" : "{$this->gateway}partner={$this->partner}&notify_id={$_POST['notify_id']}";
		$result = $this->_verify($url);
		
		$filtered = $this->filter($_POST);
		$sorted = $this->sort($filtered);
		$this->mysign = $this->buildMysign($sorted);
		
		if (preg_match('/true$/i',$result) && $this->mysign == $_POST['sign']) 
		{
			return true;
		}
		
		return false;
	}
	
	/**
	 *  同步通知
	 */
	public function returnVerify()
	{
		if (empty($_GET)) 
		{
			return false;
		}
		
		$url = ($this->transport == 'https') ? "{$this->gateway}service=notify_verify&partner={$this->partner}&notify_id={$_GET['notify_id']}" : "{$this->gateway}partner={$this->partner}&notify_id={$_GET['notify_id']}";
		$result = $this->_verify($url);
		
		$filtered = $this->filter($_GET);
		$sorted = $this->sort($filtered);
		$this->mysign = $this->buildMysign($sorted);
		
		if (preg_match('/true$/i',$result) && $this->mysign == $_GET['sign']) 
		{
			return true;
		}
		
		return false;
	}
	
	/**
	 *  验证
	 */
	protected function _verify($url,$timeout = 60)
	{
		$urlArr = parse_url($url);
		
		if ($urlArr['scheme'] == 'https') 
		{
			$transport = 'ssl://';
			$port = '443';
		}
		else 
		{
			$transport = 'tcp://';
			$port = '80';
		}
		$host = $transport . $urlArr['host'];
		
		$errorno = '';
		$errorstr = '';
		$fp = @fsockopen($host,$port,$errorno,$errorstr,$timeout);
		if (!$fp) 
		{
			exit('验证连接失败');
		}
		$contentLength = strlen($urlArr['query']);
		fputs($fp,"POST {$urlArr['path']} HTTP/1.1\r\n");
		fputs($fp,"Host: {$urlArr['host']}\r\n");
		fputs($fp,"Content-type: application/x-www-form-urlencoded\r\n");
		fputs($fp,"Content-length: {$contentLength}\r\n");
		fputs($fp,"Connection: close\r\n\r\n");
		fputs($fp,"{$urlArr['query']}\r\n\r\n");
		
		$results = array();
		while (!feof($fp)) 
		{
			$results[] = @fgets($fp,1024);
		}
		fclose($fp);
		
		$ret = implode(',',$results);
		return $ret;
	}
	
	/**
	 *  去除空值和签名参数
	 */
	public function filter(array $params)
	{
		$rets = array();
		foreach ($params as $k => $val) 
		{
			if ($k == 'sign' || $k == 'sign_type') 
			{
				continue ;
			}
			$rets[$k] = $val;
		}
		return $rets;
	}
	
	/**
	 *  排序
	 */
	public function sort($arr)
	{
		ksort($arr);
		reset($arr);
		return $arr;
	}
	
	/**
	 *  生产签名结果
	 */
	public function buildMysign(array $sorted)
	{
		$pre = $this->_createQuery($sorted);
		$str = $pre . $this->key;
		$mysign = $this->_sign($str);
		return $mysign;
	}
	
	/**
	 *  日志
	 */
	public function log($log)
	{
		$filename = date('Ym',time());
		$fp = fopen("log/alipay/{$filename}.txt",'a');
		$date = date('d H:i:s',time());
		flock($fp,LOCK_EX);
		fputs($fp,"$log\r\n");
		fputs($fp,"：{$date}\r\n");
		flock($fp,LOCK_UN);
		fclose($fp);
	}
	
	/**
	 *  构建表单
	 */
	public function form($params)
	{
		$params = $this->filter($params);
		if (empty($params['_input_charset'])) 
		{
			$params['_input_charset'] = 'utf-8';
		}
		$params = $this->sort($params);
		$this->mysign = $this->buildMysign($params);
		
		$gateway = 'https://www.alipay.com/cooperate/gateway.do?';
		$html = "<form id=\"alipay\" name=\"alipay\" action=\"{$gateway}_input_charset={$params['_input_charset']}\" method=\"post\">";
		
		foreach ($params as $k => $val) 
		{
			$html .= "<input type=\"hidden\" name=\"{$k}\" value=\"{$val}\" />";
		}
		$html .= "<input type=\"hidden\" name=\"sign\" value=\"{$this->mysign}\" />";
		$html .= "<input type=\"hidden\" name=\"sign_type\" value=\"{$this->signType}\" />";
		$html .= '</form>';
		
		return $html;
	}
	
	/**
	 *  把数组所有元素，按照 参数=参数值 的模式用 & 字符拼接成字符串
	 */
	protected function _createQuery(array $params)
	{
		$ret = '';
		foreach ($params as $k => $val) 
		{
			$ret .= "{$k}={$val}&";
		}
		$ret = substr($ret,0,-1);
		return $ret;    /* 去掉末尾的 & */
	}
	
	/**
	 *  生成签名字符串
	 */
	protected function _sign($str)
	{
		switch ($this->signType)
		{
			case 'MD5' :
				$sign = md5($str);
				break ;
				
			default :
				exit('签名方式不支持');
		}
		
		return $sign;
	}
}

?>