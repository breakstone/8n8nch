<?php
	/**mypage**/
	###################################
	# マイページ画面
	###################################
	// config
	if(!file_exists('../_config/config.php')) 
	{
	   die('[index.php] _config/config.php not found');
	}
	require_once '../_config/config.php';
	require_once '../_includes/functions.php';
	
	
	//没有Session 跳转到登录页面
	if($_SESSION['kiwa_companyname']==""||$_SESSION['kiwa_companyid']==""){
		header('Location:'.APP_URL.'login/');
	}else{
		switch ($page){
			//主页显示工作信息
			case 'index';
				require_once 'admin.php';
				break;
			case 'set2domain';
				require_once 'set2domain.php';
				break;
			case 'qrcode';
				require_once 'qrcode.php';
				break;
			case 'cpr';
				require_once 'cpr.php';
				break;
			case 'clian';
				require_once 'clian.php';
				break;
			case 'headpic';
				require_once 'head.php';
				break;
			case 'logo';
				require_once 'logo.php';
				break;
			case 'turnpic';
				require_once 'turnpic.php';
				break;
			case 'turnpic2';
				require_once 'turnpic2.php';
				break;
			default:
			//本页处理
			if($page==""){
				require_once 'admin.php';
				break;
			}else{
				//エラー页面
				$smarty->assign('BASE_URL', APP_URL);
				$smarty->assign('THEME', THEME);
				$smarty->display('error.html');
			}
		}
	}
?>