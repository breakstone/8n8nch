<?php
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	//没有Session 跳转到登录页面
	if($_SESSION['kiwa_companyid']==""||$_SESSION['kiwa_companyname']==""){
		header('Location:'.APP_URL.'login/');
	}else{
		$id = cleanInput($id);
		$Lvli = getLvli($id);
		$user = getUser($Lvli['user_id']);

		$smarty->assign('lvli', $Lvli);
		$smarty->assign('user', $user);
		$toCompany_pr = str_replace("\r\n","<br>",$Lvli['toCompany_pr']);
		$smarty->assign('toCompany_pr', $toCompany_pr);
		
		//个人pr，取得资格处理
		$pr = str_replace("\r\n","<br>",$user['mypr']);
		$smarty->assign('pr', $pr);
		$zige = str_replace("\r\n","<br>",$user['zige']);
		$smarty->assign('zige', $zige);
		
		//注册个方法
		$smarty->register_function('getname','getName');
		$smarty->assign('THEME', THEME);
		$smarty->assign('BASE_URL', APP_URL);
		
		$smarty->assign('COMPANYNAME', $_SESSION['kiwa_companyname']);
		$smarty->assign('COMPANYID', $_SESSION['kiwa_companyid']);
		$smarty->display('Companypage/lvli.html');
	}
?>