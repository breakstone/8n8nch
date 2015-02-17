<?php
	// config
	if(!file_exists('../_config/config.php'))
	{
		die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	
	//固定引入参数
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
 	if(isMobile()){
 		$smarty->display('mobile/Requirement/saved.html');
 	}else{
 		$smarty->display('Requirement/saved.html');
 	}
 	
 	
?>