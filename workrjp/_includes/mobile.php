<?php
/** *程  序：iswap.php判断是否是通过手机访问
*版  本：Ver 1.0 beta
*修  改：奇迹方舟(imiku.com)
*最后更新：2010.11.4 22:56
*程序返回：@return bool 是否是移动设备
*该程序可以任意传播和修改，但是请保留以上版权信息!
*/
function isMobile(){
	
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$mobile_browser = Array(
		"mqqbrowser", //手机QQ浏览器
		"opera mobi", //手机opera
		"juc","iuc",//uc浏览器
		"fennec","ios","applewebKit/420","applewebkit/525","applewebkit/532","ipad","iphone","ipaq","ipod",
		
		"240x320","480x640","acer","android","anywhereyougo.com","asus","audio","blackberry","blazer","coolpad" ,"dopod", "etouch", "hitachi","htc","huawei", "jbrowser", "lenovo","lg","lg-","lge-","lge", "mobi","moto","nokia","phone","samsung","sony","symbian","tablet","tianyu","wap","xda","xde","zte"
	);
	$is_mobile = false;
	foreach ($mobile_browser as $device) {
		if (stristr($user_agent, $device)) {
			$is_mobile = true;
			break;
		}
	}
	$is_iPad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
	if($is_iPad) {
		$is_mobile = false;
	} 
	session_start();
	if(isset($_SESSION['Clienusetype'])&&$_SESSION['Clienusetype']=="mobile"){
		$is_mobile = true;
	}
	if(isset($_SESSION['Clienusetype'])&&$_SESSION['Clienusetype']=="computer"){
		$is_mobile = false;
	}
	//var_dump($_SESSION['Clienusetype']);
	return $is_mobile;
}
?>