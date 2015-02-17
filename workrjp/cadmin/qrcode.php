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
	
	$sql_yuming = "select yuming from dtb_2domain where user_id = '$userid' and del_flg = 0";
	$yuming = $db->QueryItem($sql_yuming);
	
	$smarty->assign('yuming', $yuming);
	
	$sql = "select * from dtb_companyuser where company_id = '$userid' and del_flg = 0";
	$company = $db->QueryRow($sql);
	$smarty->assign('company', $company);
	
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$smarty->display('Cadmin/qrcode.html');
	
?>