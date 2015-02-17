<?php
/**mypage**/
###################################
# マイページ画面
###################################
// config
if(!file_exists('../_config/config.php'))
{
	die('[index.php] _config/config.php not found');
}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	$userid = $_SESSION['kiwa_companyid'];
	if(isset($_REQUEST["2domain"])&&$_REQUEST["2domain"]!=""){
		$yuming = $_REQUEST["2domain"];
		$sql_see = "select Id from dtb_2domain where user_id = '$userid' and del_flg = 0";
		$Id = $db->QueryItem($sql_see);
		
		$sql = "select user_id from dtb_2domain where yuming = '$yuming' and del_flg = 0";
		$ui = $db->QueryItem($sql);
		if($ui == $userid || $ui == ""){
			if($Id){
				$sql_save = "update dtb_2domain set yuming='$yuming' where user_id = '$userid' and del_flg = 0";
				$db->Execute($sql_save);
			}else{
				$now = date("Y-m-d H:i:s");
				$sql_insert = "insert into dtb_2domain set user_id='$userid',user_type='company',yuming='$yuming',create_date='$now'";
				$db->Execute($sql_insert);
			}
		}else{
			$smarty->assign('error', "申请的2级域名已经被注册，请重新选择！");
		}
	}
	
	$sql_yuming = "select yuming from dtb_2domain where user_id = '$userid' and del_flg = 0";
	$yuming = $db->QueryItem($sql_yuming);
	$smarty->assign('yuming', $yuming);
	
	$sql = "select * from dtb_companyuser where company_id = '$userid' and del_flg = 0";
	$company = $db->QueryRow($sql);
	$smarty->assign('company', $company);
	
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$smarty->display('Cadmin/set2domain.html');
?>