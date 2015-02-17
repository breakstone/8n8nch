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
	
	if(isset($_REQUEST["lian"])){
		$pref_name = getName(array("name"=>"pref","value"=>$_REQUEST["pref"]));
		$sql_save = "update dtb_companyuser set tel01='$_REQUEST[tel01]',tel02='$_REQUEST[tel02]',tel03='$_REQUEST[tel03]',fax01='$_REQUEST[fax01]',fax02='$_REQUEST[fax02]',fax03='$_REQUEST[fax03]',
												area_cd='$_REQUEST[areaid]',pref_cd='$_REQUEST[pref]',pref='$pref_name',addr01='$_REQUEST[addr01]',addr02='$_REQUEST[addr02]',zip01='$_REQUEST[zip01]',zip02='$_REQUEST[zip02]' where company_id = '$cid' and del_flg = 0";
		
		if($db->Execute($sql_save)){
			$smarty->assign('tell', "修改成功！");
		}else{
			$smarty->assign('tell', "修改失败，请联系管理员！");
		}
	}
	
	
	$sql = "select * from dtb_companyuser where company_id = '$cid' and del_flg = 0";
	$company = $db->QueryRow($sql);
	$smarty->assign('company', $company);
	
	//get得到地域数据
	$areas = getPref();
	$smarty->assign('areas', $areas);
	if($company["area_cd"]!=""){
		$pref = getPref($company["area_cd"]);
		$smarty->assign('prefSearch', $pref);
	}
	
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$smarty->display('Cadmin/clian.html');
?>