<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	
	switch ($id){
		case 'error':
			$smarty->assign('h1', "Error");
			$smarty->assign('message', "非常抱歉，由于系统错误，请您与管理中心联系");
			break;
	}
	//固定引入参数
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	$smarty->display('Live/done.html');
?>