<?php
	###################################
	# パスワードを忘れの画面
	###################################
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	require_once '../_includes/mobile.php';
	
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	if(isMobile()){
		$smarty->display('Online/guanxiovermobile.html');
	}else{
		$smarty->display('Online/guanxiover.html');
	}
?>