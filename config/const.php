<?php

$template = Zend_Registry::get('config')->site->setting->template;

define('SITE_NAME',"友趣游");
define('DOMAIN',"http://www.youquyou.cc/");

define('URL_UPLOAD','http://www.youquyou.cc/');
define('URL_IMG',DOMAIN."static/style/{$template}/image/");
define('URL_JS',"/static/style/{$template}/js/");
define('URL_CSS',"/static/style/{$template}/css/");
define('URL_MIX',"/static/style/{$template}/mix/");
define('URL_WEB',"/static/style/{$template}/");

?>