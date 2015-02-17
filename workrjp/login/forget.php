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

	
	//错误信息
	if(isset($id)&&$id=="error"){
		$smarty->assign('_error', "※ 你所输入的邮箱尚未登录，请再次核对!");
	}
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	if(isMobile()){
		$smarty->display('mobile/Login/forget.html');
	}else{
		$smarty->display('Login/forget.html');
	}

?>