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
	
	$cid = $_SESSION['kiwa_companyid'];
	if(isset($_REQUEST["pr"])&&$_REQUEST["pr"]!=""){
		$sql_save = "update dtb_companyuser set company_text='$_REQUEST[pr]' where company_id = '$cid'";
		if($db->Execute($sql_save)){
			$smarty->assign('tell', "修改成功！");
		}else{
			$smarty->assign('tell', "修改失败，请联系管理员！");
		}
	}
	
	
	$sql = "select * from dtb_companyuser where company_id = '$cid' and del_flg = 0";
	$company = $db->QueryRow($sql);
	$smarty->assign('company', $company);
	
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$smarty->display('Cadmin/cpr.html');
	
?>