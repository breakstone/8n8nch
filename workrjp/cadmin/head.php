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
	if(isset($_REQUEST["title"])&&$_REQUEST["title"]!=""){
		
		$sql_see = "select count(*) from dtb_people_page where user_id = '$cid' and del_flg = 0";
		$count = $db->QueryItem($sql_see);
		if($count>0){
			$sql_do = "update dtb_people_page set header_title='$_REQUEST[title]' where user_id = '$cid' and del_flg = 0";
		}else{
			$sql_do = "insert into dtb_people_page set user_id = '$cid',user_type='company',header_title='$_REQUEST[title]'";
		}
		if($db->Execute($sql_do)){
			$smarty->assign('tell', "修改成功！");
		}else{
			$smarty->assign('tell', "修改失败，请联系管理员！");
		}
	}
	
	
	$sql = "select * from dtb_companyuser where company_id = '$cid' and del_flg = 0";
	$company = $db->QueryRow($sql);
	$smarty->assign('company', $company);
	
	$sql_page = "select * from dtb_people_page where user_id = '$cid' and del_flg = 0";
	$companypage = $db->QueryRow($sql_page);
	$smarty->assign('companypage', $companypage);
	
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$smarty->display('Cadmin/head.html');
	
?>