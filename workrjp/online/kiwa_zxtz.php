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
		$smarty->assign('style', "style='font-size:48px;line-height:65px;'");
	}else{
		$smarty->assign('style', "style='font-size:18px;line-height:30px;'");
	}
	
	$smarty->display('Online/kiwa_zxtz.html');
?>