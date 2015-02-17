<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	if((isset($_SESSION["kiwa_userid"])&&$_SESSION["kiwa_userid"]!="")||(isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]!="")){
	{
		$id=@$_GET['id'];
		$bbs = getBbs("bbs_id='$id'",0, 1);
		if (!empty($id)&&$bbs[0]["user_id"]==@$_SESSION["kiwa_userid"] || !empty($id)&&$bbs[0]["user_id"]==@$_SESSION["kiwa_companyid"])
		{
		
			$smarty->assign('bbs', $bbs[0]);
		
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			if(isMobile()){
				$smarty->display('mobile/Bbs/bbs_update.html');
			}else{
				$smarty->display('Bbs/bbs_update.html');
			}
		}
		else {
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			$smarty->display('error.html');
		}
		
		
	}
	}else{
			$smarty->assign('BASE_URL', APP_URL);
			$smarty->assign('THEME', THEME);
			$smarty->display('error.html');
	}

?>