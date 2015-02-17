<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	$sql="select user_id from dtb_bbs where bbs_id='$id'";
	$uid = $db->QueryItem($sql);
	
	if((isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]=="000001") || (isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]==$uid) || (isset($_SESSION["kiwa_userid"])&&$_SESSION["kiwa_userid"]==$uid)){
		
		$sql="update dtb_bbs set del_flg=1 where bbs_id='$id'";
		$db->Execute($sql);
		
		//存日志表
		dolog($uid, "", "", $id, "", "delete");
		
		header('Location:'.APP_URL.'bbs/');
	}else{
		//固定引入参数
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		$smarty->display('error.html');
	}
?>