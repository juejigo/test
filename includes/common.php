<?php
/**
 *  生成推荐人识别符
 */
function createR($memberId = 0)
{
	$r = '';
	if (empty($memberId) && Zend_Auth::getInstance()->hasIdentity())
	{
		$memberId = Zend_Auth::getInstance()->getIdentity()->id;
	}
	
	$r = base64_encode($memberId);
	return $r;
}

/**
 *  获取推荐人
 */
function getReferee()
{
	/* 获取识别符 */

	$base64 = '';
	if($_COOKIE['r'])
	{
		$base64 = Core_Cookie::get('r');
	}

	if(!empty($_GET['r']))
	{
		$base64 = sanitize($_GET['r']);
	}

	if ($base64)
	{
		$referee = base64_decode($base64,true);

		/* 推荐人是否存在 */
		$db = Zend_Registry::get('db');
		$member = $db->select()
		->from(array('m' => 'member'))
		->where("m.id = '{$referee}' or m.account = '{$referee}' or m.openid = '{$referee}'")
		->where('m.status = ?',1)
		->query()
		->fetch();

		$referee = $member;
	}

	return !empty($referee) ? $referee : array();
}

/**
 *  获取用户信息
 * 
 *  @param int $memberId
 *  @return array
 */
function getmember($memberId)
{
	$db = Zend_Registry::get('db');
	
	$member = $db->select()
		->from(array('m' => 'member'))
		->joinLeft(array('p' => 'member_profile'),'p.member_id = m.id')
		->where('m.id = ?',$memberId)
		->query()
		->fetch(Zend_Db::FETCH_ASSOC);
	
	return $member;
}

/**
 *  过滤
 * 
 *  @param mixed $polluted
 *  @return mixed
 */
function sanitize($polluted)
{
	$sanitizeChain = new Zend_Filter();
	$sanitizeChain->addFilter(new Zend_Filter_StringTrim())
		->addFilter(new Zend_Filter_HtmlEntities(array('encoding' => 'UTF-8')));
	
	if (is_array($polluted))
	{
		foreach ($polluted as $k => $v)
		{
			$polluted[$k] = sanitize($v);
		}
	}
	else if (is_string($polluted))
	{
		$polluted = preg_replace('/[\n\r]+/',' ',$polluted);
		$polluted = $sanitizeChain->filter($polluted);
	}
	
	return $polluted;
}

/**
 *  取除 htlm 标签
 * 
 *  @param string $html
 *  @param array $options 允许标签
 *  @return string
 */
function striptags($html,$options = array())
{
	$filter = new Zend_Filter_StripTags($options);
	return $filter->filter($html);
}

/**
 *  获取 IP
 * 
 *  @return string
 */
function ip()
{
	$ip = '';
	if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'),'unknow') != 0)
	{
		$ip = getenv('HTTP_CLIENT_IP');
	}
	else if (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknow') != 0)
	{
		$ip = getenv('HTTP_X_FORWARDED_FOR');
	}
	else if (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'),'unknow') != 0)
	{
		$ip = getenv('REMOTE_ADDR');
	}
	else if (!empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'],'unknow') != 0)
	{
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	
	return Zend_Validate::is($ip,'Ip') ? $ip : 'unknow';
}

/**
 *  建立身份
 * 
 *  @param int $memberId
 *  @return void
 */
function createIdentity($memberId)
{
	$db = Zend_Registry::get('db');
	$member = $db->select()
		->from(array('m' => 'member'))
		->where('m.id = ?',$memberId)
		->query()
		->fetch(Zend_Db::FETCH_ASSOC);
		
	$identity = new stdClass();
	$identity->id = $member['id'];
	$identity->role = $member['role'];
	$identity->group = $member['group'];
	$identity->privileges = $member['privileges'];
	Zend_Auth::getInstance()->getStorage()->write($identity);
}

/**
 *  获取缩略图地址
 * 
 *  @param string $source 源图片地址
 *  @param int $width 缩略图宽度
 *  @param string $absolutePath 绝对路径
 *  @return string
 */
function thumbpath($source = '',$width = 0,$path = '')
{
	$image = '';
	$ret = '';
	
	if (!empty($source) && is_numeric($source)) 
	{
		$db = Zend_Registry::get('db');
		$r = $db->select()
			->from(array('i' => 'image'))
			->where('i.id = ?',$source)
			->query()
			->fetch();
		$image = $r['url'];
	}
	else 
	{
		$image = $source;
	}
	
	if (empty($image)) 
	{
		$default = empty($default) ? 'pic' : $default;
		switch ($default)
		{
			case 'pic':
				$ret = URL_IMG . "public/default_pic.thumb.{$width}.jpg";
				break;
			case 'blank':
				$ret = URL_IMG . 'public/blank.gif';
				break;
			default:
				break;
		}
	}
	else 
	{
		$pathinfo = pathinfo($image);
		$ret = ($width == 0) ? $image : "{$image}.thumb.{$width}.{$pathinfo['extension']}";
	}
	
	return $ret;
}

/**
 *  上传
 * 
 *  @param string $root 根目录
 *  @param string $subDir 子目录
 *  @param string $ext 扩展名
 *  @return string
 */
function upload($file,$root,$type = 'image')
{
	$adapter = new Zend_File_Transfer();
	
	if ($type == 'image') 
	{
		$adapter->addValidator('Extension',true,'jpg,jpeg,gif,png')
			->addValidator('Size',true,1024000 * 1);
			
		if (!$adapter->isValid($file)) 
		{
			return false;
		}
	}
	
	$config = Zend_Registry::get('config');
	$ext = strtolower(strrchr($_FILES[$file]['name'],'.'));
	$destination = $config->upload->root->$root . date('Y/m/d/',time()) . date('His',time()) . mt_rand(1000,100000) . "{$ext}";
	$dirs = dirname($destination);
    if (!is_dir($dirs))
    {
    	mkdir($dirs,0777,true);
    }
	
	$adapter->addFilter('Rename',$destination,$file);
	$adapter->receive($file);
	
	$user = Zend_Auth::getInstance()->getIdentity();
	$memberid = empty($user) ? 0 : $user->id;
	$size = (float) $adapter->getFileSize();
	$uploadModel = new Model_Upload();
	$uploadModel->createRow(array(
		'member_id' => $memberid,
		'file' => $destination,
		'size' => $adapter->getFileSize()))->save();
    
    return $destination;
}

/**
 *  格式化文件大小
 * 
 *  @param int $size 以byte为单位
 *  @return string
 */
function formatFilesize($size)
{
	$units = array('Bytes','KB','MB','GB','TB','PB','EB','ZB','YB');
	$i = 0;
	
	while($size >= 1024 && $i < 8)
	{
		$size /= 1024;
		$i++;
	}
	
	$inv = 1 / 1024;
	
	while($size >= 1024 && $i < 8)
	{
		$size *= $inv;
		$i++;
	}
	
	$tmp = sprintf("%.2f",$size);
	
	return ($tmp - intval($tmp) ? $tmp : $size) . $units[$i];
}

/**
 *  登录处理
 * 
 *  建立身份,登录更新
 */
function login($memberId)
{
	$memberId = (int) $memberId;
	
	/* 建立身份 */
	
	createIdentity($memberId);
	
	/* 登录后处理 */
	
	afterLogin($memberId);
}

/**
 *  app 登录
 */
function applogin($memberId,$uid = '',$os = '') 
{
	$db = Zend_Registry::get('db');
	$memberId = (int) $memberId;
	
	/* 生成 app session */
	
	$model = new Model_AppSession();
	$row = $model->createRow(array(
		'member_id' => $memberId
	));
	$row->save();
	
	/* 设备号记录 */
	
	if ($uid != '' && $os != '')
	{
		$imeiId = 0;
		$count = $db->select()
			->from(array('i' => 'imei'),array(new Zend_Db_Expr('COUNT(*)')))
			->where('i.imei = ?',$uid)
			->where('i.os = ?',$os)
			->query()
			->fetchColumn();
		if($count == 0)
		{
			$imeiModel = new Model_Imei();
		    $imeiRow = $imeiModel->createRow(array(
		        'member_id' => $memberId,
		        'imei' => $uid,
		        'os' => $os,
		    ));
		    $imeiRow->save();
		    
		    $db->update('member',array('imei_id' => $imeiRow->id),array('id = ?' => $memberId));
		}
	}
	
	/* 登录后处理 */
	
	afterLogin($memberId);
	
	return $row->id;
}

/**
 *  匹配升级
 */
function afterLogin($memberId) 
{
	$db = Zend_Registry::get('db');
	
	$member = $db->select()
		->from(array('m' => 'member'))
		->where('m.id = ?',$memberId)
		->query()
		->fetch();
	
	/* 升级 */
	
	if ($member['role'] == 'member' && $member['group'] == 0 && $member['consumption'] > 0) 
	{
		$db->update('member',array('group' => 1),array('id = ?' => $member['id']));
	}
}

/**
 *  获取用户等级名称
 */
function getGroupName($role,$group) 
{
	$groupName = '会员';
	
	if ($role == 'member') 
	{
		switch ($group)
		{
			case 0:
				$groupName = '普通会员';
				break;
		}
	}
	
	return $groupName;
}

/**
 *  获取文件本地路径
 * 
 *  @param string $filepath
 */
function localpath($filepath)
{
	return ltrim($filepath,'/');
}

/**
 *  截取字符串
 * 
 *  @param array $params 参数
 *  @param smarty $template
 *  @return string
 */
function cutstr($str,$length,$start = 0)
{
	$start = (int) $start;
	$length = (int) $length;
	
	return mb_substr($str,$start,$length,'utf-8');
}


/**
 * 转换中文特殊字符
 */
function htmlDecodeCn($str)
{
    $str = stripslashes($str);

    $find  = array('&ldquo;','&rdquo;','&lsquo;','&rsquo;','&mdash;','&middot;','&bull;','&nbsp;','&hellip;');
    $replace = array('“','”','‘','’','—','·','•','','...');
    $str = str_replace($find,$replace,$str);

    return html_entity_decode($str);
}

/**
 *  价格格式化
 * 
 *  @param float $value
 */
function parsePrice($value,$decimal = 1)
{
	if ($decimal == 0) 
	{
		$value = preg_replace('/(.*)(\\.)([0-9]*?)0+$/','\1\2\3', number_format($value,2,'.',''));
	    if (substr($value,-1) == '.')
	    {
	        $value = substr($value,0,-1);
	    }
	}
	return "<span class=\"price-tag\">¥</span> <span class=\"price-value\">$value</span>";
}

/**
 *  判断微信
 */
function isWeixin()
{ 
	if (strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') !== false) 
	{
		return true;
	}	
	return false;
}

/**
 *  隐藏手机号码
 */
function hideMobile($string) 
{
	if (Zend_Validate::is($string,'MobileNumber',array(),array('Core2_Validate'))) 
	{
		return preg_replace('/(1\d{2})\d\d\d\d(\d{4})/',"\$1*****\$2",$string);
	}
	
	return $string;
}

/**
 *  隐藏帐号
 */
function hideAccount($string) 
{
	$leng = mb_strlen($string,'utf8');
	if($leng>10){
		$account = mb_substr($string,0,3,'utf8');
		for($i=0;$i<$leng-6;$i++){
		  $account .= '*';
		}
		$account .= mb_substr($string,$leng-3,$leng,'utf8');
	}else if($leng>5){
		$account = mb_substr($string,0,2,'utf8');
		for($i=0;$i<$leng-4;$i++){
		  $account .= '*';
		}
		$account .= mb_substr($string,$leng-2,$leng,'utf8');
	}else{
		$account = mb_substr($string,0,1,'utf8').'**';
	}
	//echo $account;exit;
	return $account;
}

/**
 *  浏览器友好的变量输出
 */
function dump($var, $echo=true,$label=null, $strict=true){
    $label = ($label===null) ? '' : rtrim($label) . ' ';
    if(!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = "<pre>".$label.htmlspecialchars($output,ENT_QUOTES)."</pre>";
        } else {
            $output = $label . " : " . print_r($var, true);
        }
    }else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if(!extension_loaded('xdebug')) {
            $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
            $output = '<pre>'. $label. htmlspecialchars($output, ENT_QUOTES). '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else
        return $output;
}

/**
 *  CURL GET提交
 */
function httpGet($url) {
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

/**
 *  cookie加密解密
 */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

	$ckey_length = 4;	//note 随机密钥长度 取值 0-32;
				//note 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
				//note 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
				//note 当此值为 0 时，则不产生随机密钥

	$key = md5($key ? $key : 'mxj2015');
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}

/**
 *  URL重新组装
 *  $url 原始URL
 *  $scrapField 重置字段
 */
function resetUrl($url,$resetField)
{
	$fields = explode(',',$resetField);
	$parse = parse_url($url);
	if(isset($parse['query']))
	{
		parse_str($parse['query'],$params);
		foreach($fields as $field)
		{
		  unset($params[$field]);
		}
		$resetUrl   =  $parse['path'].'?'.http_build_query($params);
	}
	else
	{
		$resetUrl = $url;
	}
	return $resetUrl;
}

/**
 *  判断是否是手机设备
 */
function isMobile() 
{
	//return true;
	$userAgent = $_SERVER['HTTP_USER_AGENT'];
	$mobileAgents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
	$isMobile = false;
	foreach ($mobileAgents as $device) 
	{
		if (stristr($userAgent,$device)) 
		{
			$isMobile = true;
			break;
		}
	}
	
	return $isMobile;
}

?>