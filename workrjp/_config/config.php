<?php
/**
 * workr
 * @author     joett
 * @license    1.1
 *             
 */
	# 301重定向
	$the_host = $_SERVER['HTTP_HOST'];
	$request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
	if($the_host == 'www.8n8n.co.jp'){
		header('HTTP/1.1 301 Moved Permanently');
		header('Status: 301 Moved Permanently');
		header('Location: http://8n8n.co.jp'.$request_uri);
	}
	# 301重定向
	
	define('APP_PATH', str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, str_replace('_config', '', dirname(__FILE__)) . DIRECTORY_SEPARATOR));
	######################################
	# 环境配置文件
	######################################
	require_once APP_PATH . '_config/config.envs.php';
	
	######################################
	# 引入数据库
	######################################
	require_once APP_PATH . '_includes/class.Db.php';
	// Setup database connection
	try {
		$db = new Db(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
		$db->Execute('SET NAMES UTF8');
	}catch(ConnectException $exception){
		if (ENVIRONMENT == 'dev'){
			echo "Database Connection Error:<br/>";
			print_r($exception->getMessage());	
		}
	}
	
	######################################
	# 设定smarty参数
	######################################
	require_once APP_PATH . '_includes/smarty/libs/Smarty.class.php';
	// Setup Smarty
	$smarty = new Smarty();
	$smarty->template_dir = APP_PATH . '_templates' . DIRECTORY_SEPARATOR . THEME . DIRECTORY_SEPARATOR;
	$smarty->compile_dir = APP_PATH .'_templates' . DIRECTORY_SEPARATOR . THEME . DIRECTORY_SEPARATOR . '_cache';
	
	######################################
	# URL重写
	######################################
	if(isset($_SERVER['SCRIPT_NAME'])){
		$app_main_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']),'/\\');
		define('_APP_MAIN_DIR', $app_main_dir);
  	}else{
		die('[config.php] Cannot determine APP_MAIN_DIR, please set manual and comment this line');
  	}
  	// Split URL - get parameters
	$_app_info['params'] = array();
	if (isset($_SERVER['HTTP_X_ORIGINAL_URL'])){
		$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_ORIGINAL_URL'];
	}
	if (isset($_SERVER['HTTP_X_REWRITE_URL'])){
		$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
	}
	// if server is Apache:	
	if(REWRITE_MODE == 'apache_mod_rewrite' || REWRITE_MODE == 'iis_isapi_rewrite'){
		$newUrl = str_replace('/', '\/', _APP_MAIN_DIR);
	    $pattern = '/'.$newUrl.'/';
	    $_url = preg_replace($pattern, '', $_SERVER['REQUEST_URI'], 1);
		$_tmp = explode('?', $_url);
		$_url = $_tmp[0];	
		if ($_url = explode('/', $_url)){
			foreach ($_url as $tag){
				if ($tag){
					$_app_info['params'][] = $tag;
				}
			}
		}
	}elseif(REWRITE_MODE == 'iis_url_rewrite'){
		if(isset($_GET['page']))
			$_app_info['params'][]  = $_GET['page'];
		if(isset($_GET['id']))
			$_app_info['params'][]  = $_GET['id'];
		if(isset($_GET['extra']))
			$_app_info['params'][]  = $_GET['extra'];
	}
	//page:页面 id:参数1 extra:参数2
	$page = (isset($_app_info['params'][0]) ? $db->real_escape_string($_app_info['params'][0]) : '');
	$id = (isset($_app_info['params'][1]) ? $db->real_escape_string($_app_info['params'][1]) : '');
	$extra = (isset($_app_info['params'][2]) ? $db->real_escape_string($_app_info['params'][2]) : '');
	
	//echo $page."-".$id."-".$extra;
	
	error_reporting(0);
	header('Content-Type: text/html; charset=UTF-8');
	ini_set('session.cookie_domain','.8n8n.co.jp');
	session_set_cookie_params (0 , '/', '.8n8n.co.jp');
	session_start();
	
	##########################################
	# 通用 --> 得到IP
	##########################################
	if (getenv("HTTP_CLIENT_IP"))
		$ipd = getenv("HTTP_CLIENT_IP");
	else if(getenv("HTTP_X_FORWARDED_FOR"))
		$ipd = getenv("HTTP_X_FORWARDED_FOR");
	else if(getenv("REMOTE_ADDR"))
		$ipd = getenv("REMOTE_ADDR");
	else $ipd = "Unknow";
	
	
	//代理IP直接退出
	empty($_SERVER['HTTP_VIA']) or exit('Access Denied');
	//刷新太多ip踢出
	
	if($ipd=="118.237.88.123") exit('Access Denied');
	
	//防止快速刷新
	$seconds = '3'; //时间段[秒]
	$refresh = '8'; //刷新次数
	//设置监控变量
	$cur_time = time();
	if(isset($_SESSION['last_time'])){
	    $_SESSION['refresh_times'] += 1;
	}else{
	    $_SESSION['refresh_times'] = 1;
	    $_SESSION['last_time'] = $cur_time;
	}
	//处理监控结果
	if($cur_time - $_SESSION['last_time'] < $seconds){
	    if($_SESSION['refresh_times'] >= $refresh){
    		
    		$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	    	
	    	$create_date = date("Y-m-d H:i:s");
	        $sql = "insert into access_denied_ip(ip,url,create_date)values('$ipd','$url','$create_date')";
	        $db->Execute($sql);
	        //跳转至攻击者服务器地址
	        header(sprintf('Location:%s', 'http://127.0.0.1'));
	        exit('Access Denied');
	    }
	}else{
	    $_SESSION['refresh_times'] = 0;
	    $_SESSION['last_time'] = $cur_time;
	}

?>