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
	
	
	switch ($id){
		case 'soxinseko':
			$smarty->assign('h1', "密码重置");
			$smarty->assign('message', "请查看您的邮箱，密码已经发送给你！");
			break;
		case 'upseko':
			$smarty->assign('h1', "密码重置成功");
			$smarty->assign('message', "密码重置成功！<br>登陆<a href='".APP_URL."login/'>这里</a>");
			break;
		default:
			$smarty->assign('h1', "系统错误");
			$smarty->assign('message', "十分抱歉。系统发生错误，把您发生的问题，请联系管理中心！");
			break;
	}
	
	$smarty->assign('BASE_URL', APP_URL);
	$smarty->assign('THEME', THEME);
	if(isMobile()){
		$smarty->display('mobile/Login/done.html');
	}else{
		$smarty->display('Login/done.html');
	}
	
?>