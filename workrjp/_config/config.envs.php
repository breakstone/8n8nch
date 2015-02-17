<?php
/**
 * Define environments
 *
 */
// local (http://localhost/jobberbase/public)
$__instances['local'] = array(
	// should be a unique part of the url (or the entire url if you wish)
	'prefix' => 'localhost',
	// mysql credentials 
	'db_host' => '157.7.137.37',
	'db_port' => 3306,
	'db_user' => 'kiwaWorkr',
	'db_password' => 'My35270708Sql',
	'db_name' => 'workr',
	'db_prefix' => '',
	// your site's full url
	'app_url' => 'http://localhost/workr/',
	//风格模板路径
	'theme' => 'ep',
	//'theme' => 'default',
	//error reporting
	'ini_error_reporting' => E_ALL,
	'ini_display_errors' => 'On',
	// environment setting 1 (use 'local' for localhost/testing OR 'online' for live, production environment)
	'location' => 'local',
	// environment setting 2 (use 'dev' together with 'local' in the previous setting OR 'prod' with 'online')
	'environment' => 'dev',
	//'apache_mod_rewrite', 'iis_url_rewrite' -microsoft URL Rewrite module, 'iis_isapi_rewrite'
	'rewrite_mode' => 'apache_mod_rewrite'
);

// live (http://www.yourjobberbasedomain.com)
$__instances['192.168.100.150'] = array(
	'prefix' => '192.168.100.150',
	'db_host' => '192.168.100.150',
	'db_port' => 3306,
	'db_user' => 'kiwaWorkr',
	'db_password' => 'My35270708Sql',
	'db_name' => 'workr',
	'db_prefix' => '',
	'theme' => 'default',
	'app_url' => 'http://192.168.100.150/',
	'theme' => 'default',
	'ini_error_reporting' => E_ALL,
	'ini_display_errors' => 'On',
	'location' => 'online',
	'environment' => 'prod',
	'rewrite_mode' => 'apache_mod_rewrite'
);
// live (http://www.yourjobberbasedomain.com)
$__instances['157.7.137.37'] = array(
	'prefix' => '157.7.137.37',
	'db_host' => 'localhost',
	'db_port' => 3306,
	'db_user' => 'kiwaWorkr',
	'db_password' => 'My35270708Sql',
	'db_name' => 'workr',
	'db_prefix' => '',
	'theme' => 'ep',
	'app_url' => 'http://157.7.137.37/',
	'theme' => 'ep',
	'ini_error_reporting' => E_ALL,
	'ini_display_errors' => 'On',
	'location' => 'online',
	'environment' => 'prod',
	'rewrite_mode' => 'apache_mod_rewrite'
);
$__instances['8n8n.co.jp'] = array(
	'prefix' => '8n8n.co.jp',
	'db_host' => 'localhost',
	'db_port' => 3306,
	'db_user' => 'kiwaWorkr',
	'db_password' => 'My35270708Sql',
	'db_name' => 'workr',
	'db_prefix' => '',
	'theme' => 'ep',
	'app_url' => 'http://8n8n.co.jp/',
	'theme' => 'ep',
	'ini_error_reporting' => E_ALL,
	'ini_display_errors' => 'On',
	'location' => 'online',
	'environment' => 'prod',
	'rewrite_mode' => 'apache_mod_rewrite'
);
$__instances['www.8n8n.co.jp'] = array(
		'prefix' => 'www.8n8n.co.jp',
		'db_host' => 'localhost',
		'db_port' => 3306,
		'db_user' => 'kiwaWorkr',
		'db_password' => 'My35270708Sql',
		'db_name' => 'workr',
		'db_prefix' => '',
		'theme' => 'ep',
		'app_url' => 'http://www.8n8n.co.jp/',
		'theme' => 'ep',
		'ini_error_reporting' => E_ALL,
		'ini_display_errors' => 'On',
		'location' => 'online',
		'environment' => 'prod',
		'rewrite_mode' => 'apache_mod_rewrite'
);
// setup current instance
foreach ($__instances as $__instance)
{
	// http requests
	if (isset($_SERVER['HTTP_HOST']))
	{
		$_compare_to = $_SERVER['HTTP_HOST'];
	}

	if (strstr($_compare_to, $__instance['prefix']))
	{
		define('DB_HOST', $__instance['db_host']);
		define('DB_PORT', $__instance['db_port']);
		define('DB_USER', $__instance['db_user']);
		define('DB_PASS', $__instance['db_password']);
		define('DB_NAME', $__instance['db_name']);
		define('DB_PREFIX', $__instance['db_prefix']);

		// values kind of redundant, they indicate wether this is a live/production or dev/testing environment
		define('LOCATION', $__instance['location']);
		define('ENVIRONMENT', $__instance['environment']);
		
		// base url of the app
		define('APP_URL', $__instance['app_url']);
		define('REWRITE_MODE', $__instance['rewrite_mode']);
		//风格模板路径
		define('THEME', $__instance['theme']);
		
		// error reporting
		ini_set('error_reporting', $__instance['ini_error_reporting']);
		ini_set('display_errors', $__instance['ini_display_errors']);
		
		break;
	}
}

#############################################
# 配置邮件信息
# info8n8n@gmail.com 
# 8n8n4888
#############################################
define('MAILHOST', "ssl://smtp.gmail.com:465");
#define('MAILHOST', "smtp.mail.yahoo.co.jp");
#define('MAILPORT', 587);
define('MAILUSER', "8n8ninfo@gmail.com");
define('MAILPASSWORD', "8n8n4888");
define('MAILFROM', "8n8ninfo@gmail.com");
define('MAILFROMNAME', "帮帮网");
?>