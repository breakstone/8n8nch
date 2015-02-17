<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	if((isset($_SESSION["kiwa_userid"])&&$_SESSION["kiwa_userid"]!="")||(isset($_SESSION["kiwa_companyid"])&&$_SESSION["kiwa_companyid"]!="")){
		
		//注册函数
		$smarty->register_function('getname','getName');
		//固定引入参数
		$smarty->assign('BASE_URL', APP_URL);
		$smarty->assign('THEME', THEME);
		if(isMobile()){
			$smarty->display('mobile/Bbs/send.html');
		}else{
			$smarty->display('Bbs/send.html');
		}
	
	}else{
		//固定引入参数
		header('Location:'.APP_URL.'login/?url=bbs_send');
	}
?>