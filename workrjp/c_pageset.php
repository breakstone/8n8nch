<?php
// config
if(!file_exists('_config/config.php'))
{
	die('[index.php] _config/config.php not found');
}
require_once '_config/config.php';
require_once '_includes/functions.php';
require_once '_includes/SubPages.php';

$yuming = str_replace(".8n8n.co.jp","",$_SERVER['HTTP_HOST']);

$sql="select * from dtb_2domain where yuming='$yuming' and del_flg = 0";
$url = $db->QueryRow($sql);

$color = $_GET["color"];

//每页显示几条;//得到当前是第几页;
if(strlen($url["user_id"]) <= 12 && ((@$_SESSION["kiwa_userid"] == $url["user_id"]) || (@$_SESSION["kiwa_companyid"] == $url["user_id"]))){
	//注册个根据id查找name的方法
	$smarty->register_function("getname","getName");
	if($url["user_type"] == "company"){
		$company = getCompany($url["user_id"]);
		//企业信息
		if($company){
			$page_color = "sp_shop_black.css";
			if($color=="red"){
				$page_color = "sp_shop_red.css";
			}
			if($color=="black"){
				$page_color = "sp_shop_black.css";
			}
			if($color=="green"){
				$page_color = "sp_shop_green.css";
			}
			if($color=="blue"){
				$page_color = "sp_shop_blue.css";
			}
			if($color=="orange"){
				$page_color = "sp_shop_orange.css";
			}
			if($color=="purple"){
				$page_color = "sp_shop_purple.css";
			}
			$sql = "update dtb_companyuser set page_color='$page_color' where company_id = '$url[user_id]'";
			$db->Execute($sql);
			header('Location:http://'.$_SERVER['HTTP_HOST']);
		}else{
			//没有找到
			$smarty->assign('h1', "信息提示");
			$smarty->assign('message', "非常抱歉，您指定的企业信息的登载已经结束。感谢您的关注！");
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			$smarty->display('People/done.html');
		}
	}
	if($url["user_type"] == "user"){
		$user = getUser($url["user_id"]);
		if($user){
			$page_color = "sp_shop_red.css";
			if($color=="red"){
				$page_color = "sp_shop_red.css";
			}
			if($color=="black"){
				$page_color = "sp_shop_black.css";
			}
			if($color=="green"){
				$page_color = "sp_shop_green.css";
			}
			if($color=="blue"){
				$page_color = "sp_shop_blue.css";
			}
			if($color=="orange"){
				$page_color = "sp_shop_orange.css";
			}
			if($color=="purple"){
				$page_color = "sp_shop_purple.css";
			}
			$sql = "update dtb_user set page_color='$page_color' where user_id = '$url[user_id]'";
			$db->Execute($sql);
			header('Location:http://'.$_SERVER['HTTP_HOST']);
		}else{
			//没有找到
			$smarty->assign('h1', "信息提示");
			$smarty->assign('message', "非常抱歉，您指定的人才信息的登载已经结束。感谢您的关注！");

			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			$smarty->display('People/done.html');
		}
	}
}else{
	//id不合法跳入error
	//固定引入参数
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$smarty->display('error.html');
}
?>