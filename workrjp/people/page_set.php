<?php
// config
if(!file_exists('../_config/config.php'))
{
	die('[index.php] _config/config.php not found');
}
require_once '../_config/config.php';
require_once '../_includes/functions.php';
require_once '../_includes/SubPages.php';

$id = explode("_",$id);
$color = $_GET["color"];

//每页显示几条;//得到当前是第几页;
if(strlen($id[1]) <= 12 && ((@$_SESSION["kiwa_userid"] == $id[1]) || (@$_SESSION["kiwa_companyid"] == $id[1]))){
	//注册个根据id查找name的方法
	$smarty->register_function("getname","getName");
	if($id[0] == "company"){
		$company = getCompany($id[1]);
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
			$sql = "update dtb_companyuser set page_color='$page_color' where company_id = '$id[1]'";
			$db->Execute($sql);
			header('Location:'.APP_URL.'people/show/'.$id[0].'_'.$id[1].'/');
		}else{
			//没有找到
			$smarty->assign('h1', "信息提示");
			$smarty->assign('message', "非常抱歉，您指定的企业信息的登载已经结束。感谢您的关注！");
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			$smarty->display('People/done.html');
		}
	}
	if($id[0] == "user"){
		$user = getUser($id[1]);
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
			$sql = "update dtb_user set page_color='$page_color' where user_id = '$id[1]'";
			$db->Execute($sql);
			header('Location:'.APP_URL.'people/show/'.$id[0].'_'.$id[1].'/');
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